<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Kategori;
use App\Models\Kuesioner;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\AnswerKuesioner;
use App\Models\Excel;
use App\Models\FormKepuasan;
use App\Models\SubKuesioner;
use App\Models\Provinsi;
use App\Models\KabKota;
use App\Models\QuestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $thn = date('Y');
        $prodi = Prodi::where('id', '!=', 1)->get();

        $countUserStatusByProdi = (User::getCountUserStatusByProdi(request('dari_tahun'), request('ke_tahun')));
        // dd($countUserStatusByProdi);

        $data = [
            'user' => $user,
            'title' => 'Dashboard',
            'menu' => 'Dashboard',
            'submenu' => 'Dashboard',
            'column' =>  $countUserStatusByProdi,
            'alumni' => User::where('role_id', 2)->get(),
            'UNCOMPLETE' => User::where(['role_id' => 2, 'status' => 'UNCOMPLETE'])->count(),
            'PROCESS' => User::where(['role_id' => 2, 'status' => 'PROCESS'])->count(),
            'COMPLETE' => User::where(['role_id' => 2, 'status' => 'COMPLETE'])->count()
        ];
        return view('admin.index', $data);
    }

    public function tiny()
    {
        return view('admin.tiny');
    }

    public function tabelrekap()
    {
        $user = Auth::user();
        set_time_limit(360);
        $lulusan = User::where('role_id', 2)
            ->select(DB::raw('YEAR(tahun_keluar) AS tahun, COUNT(id) AS jumlah'))
            ->groupBy('tahun')->limit(5)
            ->orderBy('tahun', 'DESC')
            ->get();
        $fakultas = Fakultas::where('id', '!=', 1)->get();

        $prodi = Prodi::where('id', '!=', 1)->get();

        $array_alumni = [];

        foreach ($lulusan as $v) {
            $jml_alumni = User::where('role_id', 2)
                ->select(DB::raw('prodi_id, count(*) as total'))
                ->groupBy('prodi_id')
                ->whereYear('tahun_keluar', $v->tahun)
                ->get();
            $array_alumni += [$v->tahun => $jml_alumni];
        }

        $data = [
            'user' => $user,
            'title' => 'Tabel Rekap',
            'menu' => 'Tabel Rekap',
            'submenu' => 'Tabel Rekap',
            'lulusan' => $lulusan,
            'fakultas' => $fakultas,
            'prodi' => $prodi,
            'jml_alumni' => $array_alumni,
        ];
        return view('admin.tabel_rekap', $data);
    }

    //This is for migrate database from asnwer_kuesioner to quest_answer
    public function migrate_answer()
    {
        $users = User::all();
        $completed = 0;
        $uncompleted = 0;

        foreach ($users as $user) {
            $old_answer = AnswerKuesioner::where('users_id', $user->id)->get();
            $timedata = AnswerKuesioner::where('users_id', $user->id)->latest()->first();
            $user_answer = [];

            foreach ($old_answer as $answer) {
                foreach (json_decode($answer->answer, true) as $key => $value) {
                    if ($key == "f8") {
                        if ($value == 4) {
                            $user_answer[$key][0] = 4;
                        } else {
                            $user_answer[$key][0] = 0;
                        }
                        if ($value == 3) {
                            $user_answer[$key][1] = 3;
                        } else {
                            $user_answer[$key][1] = 0;
                        }
                        if ($value == 1 || $value == 2) {
                            $user_answer[$key][2] = $value;
                        } else {
                            $user_answer[$key][2] = 0;
                        }
                    } else {
                        $user_answer[$key] = $value;
                    }
                }
            }

            if ($user_answer != []) {
                $quest_answer = new QuestAnswer;
                $quest_answer->user_id = $user->id;
                $quest_answer->answer = json_encode($user_answer);
                $quest_answer->created_at = $timedata->created_at;
                $quest_answer->updated_at = $timedata->updated_at;
                $quest_answer->save();

                // echo print_r($user_answer);
                echo 'success';
                echo '<hr>';
                $completed++;
            } else {
                echo 'no answer <hr>';
                $uncompleted++;
            }
        }

        echo "<hr> Completed : " . $completed;
        echo "<hr> Uncompleted : " . $uncompleted;
    }

    public function migrations($file_name)
    { //for migrations Database
        Artisan::call('migrate:refresh --force --path=database/migrations/' . $file_name . '.php');

        return redirect()->route("admin");
    }

    public function fixDataQuest()
    {
        $totalReq = 15;
        $allUsers = User::where([['status', 'UNCOMPLETE'], ['prodi_id', '!=', '1'], ['role_id', 2]])
            ->join('quest_answer', 'quest_answer.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'quest_answer.answer')
            ->get();

        foreach ($allUsers as $user) {

            $questAmount    = sizeof((array) json_decode($user->answer));
            $userData       = User::find($user->id);

            if ($questAmount >= $totalReq) {

                $userData->status = 'COMPLETE';
            } else if ($questAmount > 0) {

                $userData->status = 'PROCESS';
            }

            $userData->save();

            $newUserData    = User::find($user->id);
            $userName       = $newUserData->name;
            $userStatus     = $newUserData->status;

            echo $userName . ' = ' . $userStatus . '<hr>';
        }
    }

    public function form_kepuasan()
    {
        $user = Auth::user();

        $data = [
            'user' => $user,
            'title' => 'Form Kepuasan',
            'menu' => 'Kuesioner',
            'submenu' => 'Form Kepuasan',
            'form_kepuasan' => FormKepuasan::filter(request(['cari']))->latest()->paginate(10)
        ];
        return view('admin.kuesioner.form_kepuasan', $data);
    }

    public function detail_alumni($id_prodi, $tahun)
    {
        $id = Crypt::decrypt($id_prodi);
        $thn = Crypt::decrypt($tahun);
        $user = Auth::user();

        $users = User::filter(request(['cari', 'sortByStatus']))
            ->where(['role_id' => 2, 'prodi_id' => $id])
            ->whereYear('tahun_keluar', $thn)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $dataChart = User::select('status as label', DB::raw('COUNT(*) AS y'))->groupBy('status')->where(['role_id' => 2, 'prodi_id' => $id])->whereYear('tahun_keluar', $thn)->get();
        // dd($dataChart);
        $prodi = Prodi::find($id);
        $data = [
            'user' => $user,
            'title' => 'Detail ' . $prodi->prodi . ' Tahun ' . $thn,
            'menu' => 'Dashboard',
            'submenu' => 'Dashboard',
            'chart' => json_encode($dataChart, JSON_NUMERIC_CHECK),
            'prodi' => $prodi,
            'alumni' => $users
        ];
        return view('admin.alumni.detail_alumni', $data);
    }

    public function detail_pengguna($id_role)
    {
        $user = Auth::user();
        $id = Crypt::decrypt($id_role);

        $users = User::filter(request(['cari']))->where('role_id', $id)->latest()->paginate(10);
        $role = Role::find($id);
        $data = [
            'user' => $user,
            'title' => 'Daftar Pengguna ' . $role->role,
            'menu' => 'Data Pengguna',
            'submenu' => 'Daftar Pengguna',
            'prodi' => Prodi::all(),
            'fakultas' => Fakultas::all(),
            'roles' => Role::all(),
            'users' => $users
        ];
        return view('admin.pengguna.daftar_pengguna', $data);
    }

    public function pengguna()
    {
        $role = Role::all();
        $count = [];
        foreach ($role as $item) {
            $roleUser = User::where('role_id', $item->id)->count();
            $count += [
                $item->id => $roleUser
            ];
        }

        $user = Auth::user();
        $data = [
            'user' => $user,
            'title' => 'Data Pengguna',
            'menu' => 'Data Pengguna',
            'submenu' => 'Roles',
            'roles' => Role::paginate(10),
            'role' => $count
        ];
        return view('admin.pengguna.roles', $data);
    }

    public function data_alumni()
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $users = User::filter(request(['cari', 'sortByStatus']))
                ->select('users.*')
                ->join('prodi', 'prodi.id', '=', 'users.prodi_id')
                ->join('fakultas', 'fakultas.id', '=', 'prodi.fakultas_id')
                ->where('fakultas.id', $user->prodi->fakultas->id)
                ->where('role_id', 2)
                ->orderBy('users.id', 'DESC')->paginate(10);
        } else {
            $users = User::filter(request(['cari', 'sortByStatus']))->where('role_id', 2)->orderBy('id', 'DESC')->paginate(10);
        }

        $success_msg = [];
        if (Session::get("success")) {
            $success_msg = explode("\n", Session::get("success"));
        }

        $data = [
            'user' => $user,
            'title' => 'Data Alumni',
            'menu' => 'Alumni',
            'submenu' => 'Data Alumni',
            'prodi' => Prodi::all(),
            'alumni' => $users,
            'success_msg' => $success_msg
        ];
        return view('admin.alumni.data_alumni', $data);
    }

    public function import()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'title' => 'Import Data',
            'menu' => 'Alumni',
            'submenu' => 'Data Alumni',
        ];
        return view('admin.alumni.import', $data);
    }

    public function file_import(Request $request)
    {
        // validasi
        set_time_limit(1800);
        $request->validate([
            'files' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('files');
        $nama_file = $file->getClientOriginalName();
        $file->move(public_path() . '/DataUsers', $nama_file);

        $array = FacadesExcel::toArray(new UsersImport, public_path('/DataUsers/' . $nama_file));

        $flash_msg = [];

        $jumlah_data = count($array[0]);
        for ($i = 1; $i < $jumlah_data; $i++) {
            $cek = User::where('npm', $array[0][$i]['2'])->first();
            if (!$cek) {
                User::create([
                    'name' => $array[0][$i]['1'],
                    'username' => $array[0][$i]['2'],
                    'npm' => $array[0][$i]['2'],
                    'no_telp' => ($array[0][$i]['3']) ? $array[0][$i]['3'] : '',
                    'ipk' => ($array[0][$i]['4']) ? $array[0][$i]['4'] : '',
                    'tgl_lahir' => ($array[0][$i]['6']) ? $array[0][$i]['6'] : '',
                    'tempat_lahir' => ($array[0][$i]['5']) ? $array[0][$i]['5'] : '',
                    'alamat' => ($array[0][$i]['7']) ? $array[0][$i]['7'] : '',
                    'jenis_kelamin' => ($array[0][$i]['8']) ? $array[0][$i]['8'] : '',
                    'agama' => ($array[0][$i]['9']) ? $array[0][$i]['9'] : '',
                    'tahun_masuk' => ($array[0][$i]['11']) ? $array[0][$i]['11'] : '',
                    'tahun_keluar' => ($array[0][$i]['12']) ? $array[0][$i]['12'] : '',
                    'image' => 'default.png',
                    'email' => ($array[0][$i]['10']) ? $array[0][$i]['10'] : '',
                    'password' => Hash::make($array[0][$i]['2']),
                    'status' => 'UNCOMPLETE',
                    'role_id' => '2',
                    'prodi_id' => $array[0][$i]['14'] ?? '',
                    'nik' => ($array[0][$i]['15']) ? $array[0][$i]['15'] : '',
                    'pembimbing' => ($array[0][$i]['16']) ? $array[0][$i]['16'] : '',
                    'penguji' => ($array[0][$i]['17']) ? $array[0][$i]['17'] : '',
                    'judul_skripsi' => ($array[0][$i]['18']) ? $array[0][$i]['18'] : ''
                ]);
                array_push($flash_msg, $array[0][$i]['2'] . ' ' . $array[0][$i]['1'] . ' (Create)');
            } else {
                User::where('npm', $array[0][$i]['2'])
                    ->update([
                        'name' => $array[0][$i]['1'],
                        'username' => $array[0][$i]['2'],
                        'no_telp' => ($array[0][$i]['3']) ? $array[0][$i]['3'] : '',
                        'ipk' => ($array[0][$i]['4']) ? $array[0][$i]['4'] : '',
                        'tgl_lahir' => ($array[0][$i]['6']) ? $array[0][$i]['6'] : '',
                        'tempat_lahir' => ($array[0][$i]['5']) ? $array[0][$i]['5'] : '',
                        'alamat' => ($array[0][$i]['7']) ? $array[0][$i]['7'] : '',
                        'jenis_kelamin' => ($array[0][$i]['8']) ? $array[0][$i]['8'] : '',
                        'agama' => ($array[0][$i]['9']) ? $array[0][$i]['9'] : '',
                        'tahun_masuk' => ($array[0][$i]['11']) ? $array[0][$i]['11'] : '',
                        'tahun_keluar' => ($array[0][$i]['12']) ? $array[0][$i]['12'] : '',
                        'image' => 'default.png',
                        'email' => ($array[0][$i]['10']) ? $array[0][$i]['10'] : '',
                        'password' => Hash::make($array[0][$i]['2']),
                        'role_id' => '2',
                        'prodi_id' => $array[0][$i]['14'] ?? '',
                        'nik' => ($array[0][$i]['15']) ? $array[0][$i]['15'] : '',
                        'pembimbing' => ($array[0][$i]['16']) ? $array[0][$i]['16'] : '',
                        'penguji' => ($array[0][$i]['17']) ? $array[0][$i]['17'] : '',
                        'judul_skripsi' => ($array[0][$i]['18']) ? $array[0][$i]['18'] : ''
                    ]);
                array_push($flash_msg, $array[0][$i]['2'] . ' ' . $array[0][$i]['1'] . ' (Update)');
            }
        }

        $import_msg = "";
        for ($i = 0; $i < sizeof($flash_msg); $i++) {
            $import_msg = $import_msg . "\n -" . $flash_msg[$i] . " ";
        }

        Session::flash(
            'success',
            "Berhasil Mengimport Alumni" . $import_msg
        );
        return redirect()->route('alumni');
    }

    public function template()
    {
        $filePath = public_path("template_import_data_alumni.xlsx");
        $headers = ['Content-Type: application/pdf'];
        $fileName = "template_import_data_alumni.xlsx";

        return response()->download($filePath, $fileName);
    }

    public function kategori()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'title' => 'Kategori Kuesioner',
            'menu' => 'Kuesioner',
            'submenu' => 'Kuesioner',
            'kuesioner' => Kuesioner::all(),
            'kategori' => Kategori::latest()->paginate(10)
        ];
        return view('admin.kuesioner.kategori', $data);
    }

    public function kuesioner($id)
    {
        $user = Auth::user();
        $kategori_id = Crypt::decrypt($id);

        $data = [
            'user' => $user,
            'title' => 'Kuesioner',
            'menu' => 'Kuesioner',
            'submenu' => 'Kuesioner',
            'kategori' => Kategori::all(),
            'syarat' => Kuesioner::all(),
            'kuesioner' => Kuesioner::filter(request(['cari']))->where('kategori_id', $kategori_id)->latest()->paginate(10)
        ];
        return view('admin.kuesioner.index', $data);
    }

    public function viewkuesioner()
    {

        $user = Auth::user();
        $kategori = Kategori::all();
        $Kuesioner = Kuesioner::orderBy('created_at', 'asc')->get();
        $SubKuesioner = SubKuesioner::orderBy('created_at', 'asc')->get();
        $prodi = Prodi::all();
        $provinsi = Provinsi::all();
        $perbandingan = SubKuesioner::orderBy('created_at', 'asc')->where('type', 'Perbandingan')->groupBy('deskripsi')->get();

        $data = [
            'user' => $user,
            'title' => 'View Kuesioner',
            'menu' => 'View Kuesioner',
            'submenu' => 'View Kuesioner',
            'kategori' => $kategori,
            'kuesioner' => $Kuesioner,
            'sub_kuesioner' => $SubKuesioner,
            'prodi' => $prodi,
            'provinsi' => $provinsi,
            'perbandingan' => $perbandingan,
        ];
        return view('admin.kuesioner.view_kuesioner', $data);
    }

    //REPORT
    public function excel_edit($id)
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'title' => 'Report Excel',
            'menu' => 'Report',
            'submenu' => 'Excel',
            'format' => SubKuesioner::all(),
            'excel' => Excel::find($id)
        ];
        return view('admin.report.excel_edit', $data);
    }

    public function excel()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'title' => 'Report Excel',
            'menu' => 'Report',
            'submenu' => 'Excel',
            'format' => SubKuesioner::all(),
            'prodi' => Prodi::where('id', '!=', '1')->get(),
            'excel' => Excel::filter(request(['cari']))->latest()->paginate(10)
        ];
        return view('admin.report.excel', $data);
    }

    public function grafik_post(Request $request) //New
    {
        $user = Auth::user();
        $request->validate([
            'sort' => 'required'
        ]);
        $dari_tahun = $request->input('dari_tahun');
        $ke_tahun = $request->input('ke_tahun');
        $sort = $request->input('sort');

        $value_kuesioner = SubKuesioner::where('id', $sort)->first();

        if ($dari_tahun && $ke_tahun) {
            $title = 'Report ' . $sort . ' Tahun ' . $dari_tahun . '-' . $ke_tahun;
            //perguruan tinggi
            $columnUniversitas = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id')
                ->whereYear('tahun_keluar', '>=', $dari_tahun)
                ->whereYear('tahun_keluar', '<=', $ke_tahun)
                ->get();
        } else {
            $title = 'Report ' . $sort . ' Semua Tahun';
            //perguruan tinggi
            $columnUniversitas = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id')
                ->get();
        }

        //Data For Graphics Chart
        $data_grafik = [];

        //$v for value
        foreach (json_decode($value_kuesioner->value) as $v) {
            if (preg_match("/Lainnya/i", $v)) {
                array_push($data_grafik, [
                    'name' => 'n/a',
                    'y' => 0
                ]);
            } else {
                $cek  = is_numeric($v);
                if ($cek == true) {
                    array_push($data_grafik, [
                        'name' => '(' . $v . ')',
                        'y' => 0
                    ]);
                } else {
                    array_push($data_grafik, [
                        'name' => $v,
                        'y' => 0
                    ]);
                }
            }
        }

        //Data Chart for Province and City
        $data_grafik_name = [];

        $i = 0;
        if ($sort == "f5a1" || $sort == "f5a2") {
            # Special for Province
            foreach ($columnUniversitas as $value) {

                $array = json_decode($value->answer, true);

                //Special for Province and City
                if (isset($array[$sort])) {

                    array_push($data_grafik_name, (int)$array[$sort]);
                }

                $i += 1;
                $val = (isset($array[$sort])) ? $array[$sort] : '';
                foreach ($data_grafik as $key => $item) {
                    if (($key + 1) == $val) {
                        $data_grafik[$key]['y'] += 1;
                    }
                }
            }

            $data_grafik_unique_temp = array_unique($data_grafik_name, SORT_NUMERIC);

            for ($i = 0; $i < sizeof($data_grafik_name); $i++) {
                if (isset($data_grafik_unique_temp[$i])) {
                    if ($sort == "f5a1") {
                        $region_name = Provinsi::where('id', $data_grafik_unique_temp[$i])->first();
                    } else {
                        $region_name = KabKota::where('id', $data_grafik_unique_temp[$i])->first();
                    }
                    array_push($data_grafik, [
                        'name' => $region_name["nm_wil"],
                        'y' => count(array_keys($data_grafik_name, $data_grafik_unique_temp[$i]))
                    ]);
                }
            }
        } else {
            foreach ($columnUniversitas as $value) {

                $array = json_decode($value->answer, true);

                $i += 1;
                $val = (isset($array[$sort])) ? $array[$sort] : '';
                foreach ($data_grafik as $key => $item) {
                    if (($key + 1) == $val) {
                        $data_grafik[$key]['y'] += 1;
                    }
                }
            }
        }

        $data = [
            'user' => $user,
            'title' => $title,
            'title_chart' => 'Report Grafik',
            'data_grafik_universitas' =>  json_encode($data_grafik, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Grafik'
        ];

        return view('admin.report.report_chartall', $data);
    }

    public function grafik()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'title' => 'Report Grafik',
            'menu' => 'Report',
            'submenu' => 'Grafik',
            'item' => SubKuesioner::select('sub_kuesioner.*', 'kuesioner.soal as Soal', 'kuesioner.kategori_id AS kategori_id')
                ->join('kuesioner', 'kuesioner.id', '=', 'sub_kuesioner.kuesioner_id')
                ->where('type', 'Radio Button')
                ->orWhere('type', 'Combo Box')->get()
        ];
        return view('admin.report.form_report', $data);
    }

    public function reportexcel(Request $request) //New
    {
        $id = $request->input('id_export');
        $dari_tahun = $request->input('dari_tahun');
        $ke_tahun = $request->input('ke_tahun');
        $sort = $request->input('sort');
        $title = 'All';
        $excel = DB::table('users')
            ->where(function ($query) {
                $query->where('status', 'COMPLETE')
                    ->orWhere('status', 'PROCESS');
            });

        if ($request->filled(['dari_tahun', 'ke_tahun'])) {
            $excel = $excel->whereYear('tahun_keluar', '>=', $dari_tahun)
                ->whereYear('tahun_keluar', '<=', $ke_tahun);
            $dari_tahun == $ke_tahun ? $title = $dari_tahun : $title = $dari_tahun . ' - ' . $ke_tahun;
        }

        if ($request->filled('sort')) {
            $excel = $excel->where('prodi_id', $sort);
        }

        $data_1 = [];

        foreach ($excel->get() as $value) {
            $array = [];
            if (is_object(User::find($value->id))) {
                $user_answer = User::find($value->id)->answers;
                if (isset($user_answer)) {
                    $array['tgl_kuesioner'] = $user_answer->created_at;
                    foreach (json_decode($user_answer->answer) as $key => $val) {
                        $array[$key] = $val;
                    }
                    $array['status'] = $value->status;
                }
                array_push($data_1, $array);
            }
        }

        $data = [
            'title' => 'Report Excel ' . Excel::find($id)->judul . ' ' . $title,
            'menu' => 'Report',
            'submenu' => 'Excel',
            'heading' => Excel::find($id),
            'data' => $data_1,
        ];

        return response()
            ->view('admin.report.reportexcel', $data)
            ->header('Content-type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename=' . $data['title'] . '.xls');
    }

    public function reportexcelstatus(Request $request) //New
    {
        $dari_tahun = $request->input('dari_tahun');
        $ke_tahun = $request->input('ke_tahun');
        $status = $request->input('status');
        $prodi = $request->input('prodi');
        $excel_data = DB::table('users');
        $title_y = ' All';
        $title_s = ' ';
        $title_p = ' ';

        if ($request->filled(['dari_tahun', 'ke_tahun'])) {
            $excel_data = $excel_data->whereYear('tahun_keluar', '>=', $dari_tahun)
                ->whereYear('tahun_keluar', '<=', $ke_tahun);
            $dari_tahun == $ke_tahun ? $title_y = $dari_tahun : $title_y = $dari_tahun . ' - ' . $ke_tahun;
        }

        if ($request->filled('status')) {
            $excel_data = $excel_data->where('status', $status);
            $title_s = $title_s . $status;
        }

        if ($request->filled('prodi')) {
            $excel_data = $excel_data->where('prodi_id', $prodi);
            $title_p = $title_p . Prodi::find($prodi)->prodi;
        }

        $excel = [];
        $excel1 = [];

        foreach ($excel_data->get() as $value) {
            $tgl_kues = User::find($value->id);
            if (isset($tgl_kues->answers)) {
                $excel['tgl_kuesioner'] = $tgl_kues->answers->created_at;
            } else {
                $excel['tgl_kuesioner'] = '-';
            }
            $excel['name'] = $value->name;
            $excel['npm'] = $value->npm;
            $excel['prodi'] = $value->prodi_id;
            $excel['no_telp'] = preg_replace('/[^0-9]/', '', $value->no_telp);
            $excel['tahun_keluar'] = $value->tahun_keluar;
            $excel['status'] = $value->status;

            array_push($excel1, $excel);
        }

        $heading = ['tgl_kuesioner', 'name', 'npm', 'prodi', 'no_telp', 'tahun_keluar', 'status'];

        $data = [
            'title' => 'Report Excel Perstatus' . $title_s . $title_p . $title_y,
            'menu' => 'Report',
            'submenu' => 'Excel',
            'prodi' => Prodi::pluck('prodi', 'id'),
            'heading' => $heading,
            'data' => $excel1
        ];

        return response()
            ->view('admin.report.reportexcelstatus', $data)
            ->header('Content-type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename=' . $data['title'] . '.xls');
    }

    public function gapkompetensi() //New
    {
        $user = Auth::user();
        $dari_tahun = request('dari_tahun');
        $ke_tahun = request('ke_tahun');
        if ($dari_tahun && $ke_tahun) {
            $title = 'Report GAP Kompetensi Tahun ' . $dari_tahun . '-' . $ke_tahun;
            //perguruan tinggi
            $column = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id')
                ->whereDate('tahun_keluar', '>=', $dari_tahun)
                ->whereDate('tahun_keluar', '<=', $ke_tahun)
                ->get();
        } else {
            $title = 'Report GAP Kompetensi Semua Tahun';
            //perguruan tinggi
            $column = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id')
                ->get();
        }

        $data = [
            'Etika', 'Keahlian berdasarkan bidang ilmu', 'Bahasa Inggris', 'Penggunaan Teknologi Informasi', 'Komunikasi', 'Kerja sama tim', 'Pengembangan Diri'
        ];
        $array = [];
        foreach ($data as $f) {
            array_push($array, [
                'label' => $f,
                'y' => 0
            ]);
        }

        $data_grafik = [
            'Saat Lulus' => $array,
            'Saat Dibutuhkan dalam pekerjaan' => $array
        ];

        $amount = [];
        $code = 1760;

        for ($i = 1; $i <= 14; $i++) {
            $code++;
            $amount["f" . $code] = 0;
        }

        foreach ($column as $a) {
            $decode_asnwer = json_decode($a['answer']);
            if (isset($decode_asnwer->f1761)) {
                $data_grafik['Saat Lulus'][0]['y'] += $decode_asnwer->f1761;
                $amount["f1761"] += 1;
            }
            if (isset($decode_asnwer->f1762)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][0]['y'] += $decode_asnwer->f1762;
                $amount["f1762"] += 1;
            }
            if (isset($decode_asnwer->f1763)) {
                $data_grafik['Saat Lulus'][1]['y'] += $decode_asnwer->f1763;
                $amount["f1763"] += 1;
            }
            if (isset($decode_asnwer->f1764)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][1]['y'] += $decode_asnwer->f1764;
                $amount["f1764"] += 1;
            }
            if (isset($decode_asnwer->f1765)) {
                $data_grafik['Saat Lulus'][2]['y'] += $decode_asnwer->f1765;
                $amount["f1765"] += 1;
            }
            if (isset($decode_asnwer->f1766)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][2]['y'] += $decode_asnwer->f1766;
                $amount["f1766"] += 1;
            }
            if (isset($decode_asnwer->f1767)) {
                $data_grafik['Saat Lulus'][3]['y'] += $decode_asnwer->f1767;
                $amount["f1767"] += 1;
            }
            if (isset($decode_asnwer->f1768)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][3]['y'] += $decode_asnwer->f1768;
                $amount["f1768"] += 1;
            }
            if (isset($decode_asnwer->f1769)) {
                $data_grafik['Saat Lulus'][4]['y'] += $decode_asnwer->f1769;
                $amount["f1769"] += 1;
            }
            if (isset($decode_asnwer->f1770)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][4]['y'] += $decode_asnwer->f1770;
                $amount["f1770"] += 1;
            }
            if (isset($decode_asnwer->f1771)) {
                $data_grafik['Saat Lulus'][5]['y'] += $decode_asnwer->f1771;
                $amount["f1771"] += 1;
            }
            if (isset($decode_asnwer->f1772)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][5]['y'] += $decode_asnwer->f1772;
                $amount["f1772"] += 1;
            }
            if (isset($decode_asnwer->f1773)) {
                $data_grafik['Saat Lulus'][6]['y'] += $decode_asnwer->f1773;
                $amount["f1773"] += 1;
            }
            if (isset($decode_asnwer->f1774)) {
                $data_grafik['Saat Dibutuhkan dalam pekerjaan'][6]['y'] += $decode_asnwer->f1774;
                $amount["f1774"] += 1;
            }
        }

        $data_grafik['Saat Lulus'][0]['y'] = round($data_grafik['Saat Lulus'][0]['y'] / $amount["f1761"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][0]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][0]['y'] / $amount["f1762"], 2);
        $data_grafik['Saat Lulus'][1]['y'] = round($data_grafik['Saat Lulus'][1]['y'] / $amount["f1763"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][1]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][1]['y'] / $amount["f1764"], 2);
        $data_grafik['Saat Lulus'][2]['y'] = round($data_grafik['Saat Lulus'][2]['y'] / $amount["f1765"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][2]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][2]['y'] / $amount["f1766"], 2);
        $data_grafik['Saat Lulus'][3]['y'] = round($data_grafik['Saat Lulus'][3]['y'] / $amount["f1767"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][3]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][3]['y'] / $amount["f1768"], 2);
        $data_grafik['Saat Lulus'][4]['y'] = round($data_grafik['Saat Lulus'][4]['y'] / $amount["f1769"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][4]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][4]['y'] / $amount["f1770"], 2);
        $data_grafik['Saat Lulus'][5]['y'] = round($data_grafik['Saat Lulus'][5]['y'] / $amount["f1771"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][5]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][5]['y'] / $amount["f1772"], 2);
        $data_grafik['Saat Lulus'][6]['y'] = round($data_grafik['Saat Lulus'][6]['y'] / $amount["f1773"], 2);
        $data_grafik['Saat Dibutuhkan dalam pekerjaan'][6]['y'] = round($data_grafik['Saat Dibutuhkan dalam pekerjaan'][6]['y'] / $amount["f1774"], 2);

        $data = [
            'user' => $user,
            'title' => $title,
            'title_chart' => 'Report GAP Kompetensi',
            'data_grafik' =>  $data_grafik,
            'menu' => 'Report',
            'submenu' => 'GAP Kompetensi'
        ];
        return view('admin.report.report_chartgap', $data);
    }

    // public function test_report(){ //This is For Testing
    //     $user = Auth::user();
    //     $answers = User::find(5087)->answers;

    //     print_r($answers->answer);
    //     echo '<hr>';
    // }

    //Answer questionnaire data for Chart Report, New
    public function answerQuestData($categorySession, $titleName)
    {
        $user = Auth::user();

        $fromDate       = request('dari_tahun');
        $toDate         = request('ke_tahun');
        $fromQuestDate  = request('from_date');
        $toQuestDate    = request('to_date');

        Session::flash('kategori', $categorySession);

        $column_universitas = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id');

        $column_fakultas = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id')
            ->join('prodi', 'prodi.id', '=', 'users.prodi_id')
            ->join('fakultas', 'fakultas.id', '=', 'prodi.fakultas_id');

        $column_prodi = QuestAnswer::join('users', 'users.id', '=', 'quest_answer.user_id')
            ->join('prodi', 'prodi.id', '=', 'users.prodi_id');

        if ($fromDate) {

            $column_fakultas->whereYear('tahun_keluar', '>=', $fromDate);
            $column_universitas->whereYear('tahun_keluar', '>=', $fromDate);
            $column_prodi->whereYear('tahun_keluar', '>=', $fromDate);

            Session::flash('fromDate', $fromDate);
        }

        if ($toDate) {

            $column_fakultas->whereYear('tahun_keluar', '<=', $toDate);
            $column_universitas->whereYear('tahun_keluar', '<=', $toDate);
            $column_prodi->whereYear('tahun_keluar', '<=', $toDate);

            Session::flash('toDate', $toDate);
        }

        if ($fromQuestDate) {

            $column_fakultas->whereDate('quest_answer.updated_at', '>=', $fromQuestDate);
            $column_universitas->whereDate('quest_answer.updated_at', '>=', $fromQuestDate);
            $column_prodi->whereDate('quest_answer.updated_at', '>=', $fromQuestDate);

            Session::flash('fromQuestDate', $fromQuestDate);
        }

        if ($toQuestDate) {

            $column_fakultas->whereDate('quest_answer.updated_at', '<=', $toQuestDate);
            $column_universitas->whereDate('quest_answer.updated_at', '<=', $toQuestDate);
            $column_prodi->whereDate('quest_answer.updated_at', '<=', $toQuestDate);

            Session::flash('toQuestDate', $toQuestDate);
        }

        $data = [
            'user'          => $user,
            'title'         => "Report Statistik Alumni {$titleName}", //$title,
            'universitas'   => $column_universitas->get(),
            'fakultas'      => $column_fakultas->where('fakultas.id', '!=', '1')->get(),
            'prodi'         => $column_prodi->where('prodi.id', '!=', '1')->get()
        ];

        return $data;
    }

    public function penghasilan() //Optimized, New,'
    {
        $answer_kues = $this->answerQuestData("penghasilan", "Penghasilan");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $array_prodi = [];
        foreach ($prodi as $f) {
            array_push($array_prodi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }
        $data_grafik_prodi = [
            '<= 1 Jt' => $array_prodi,
            '<= 3 Jt' => $array_prodi,
            '<= 5 Jt' => $array_prodi,
            '> 5 Jt' => $array_prodi
        ];
        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f505) && $decode_answer->f505 != 0) {
                $f505 = preg_replace('/[^0-9]/', '', $decode_answer->f505);
                switch ($f505) {
                    case $f505 <= 1000000:
                        foreach ($data_grafik_prodi['<= 1 Jt'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['<= 1 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                    case $f505 <= 3000000:
                        foreach ($data_grafik_prodi['<= 3 Jt'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['<= 3 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                    case $f505 <= 5000000;
                        foreach ($data_grafik_prodi['<= 5 Jt'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['<= 5 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                    default:
                        foreach ($data_grafik_prodi['> 5 Jt'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['> 5 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            } else {
                foreach ($data_grafik_prodi['<= 1 Jt'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['<= 1 Jt'][$key]['y'] += 1;
                    }
                }
            }
        }

        //perguruantinggi
        $data_grafik_universitas = [
            0 => [
                'label' => '<= 1 Jt',
                'y' => 0
            ],
            1 => [
                'label' => '<= 3 Jt',
                'y' => 0
            ],
            2 => [
                'label' => '<= 5 Jt',
                'y' => 0
            ],
            3 => [
                'label' => '> 5 Jt',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f505) && $decode_answer->f505 != 0) {
                $f505 = preg_replace('/[^0-9]/', '', $decode_answer->f505);
                switch ($f505) {
                    case $f505 <= 1000000:
                        $data_grafik_universitas[0]['y'] += 1;
                        break;
                    case $f505 <= 3000000:
                        $data_grafik_universitas[1]['y'] += 1;
                        break;
                    case $f505 <= 5000000;
                        $data_grafik_universitas[2]['y'] += 1;
                        break;
                    default:
                        $data_grafik_universitas[3]['y'] += 1;
                        break;
                }
            } else {
                $data_grafik_universitas[0]['y'] += 1;
            }
        }

        //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $array_fakultas = [];
        foreach ($fakultas as $f) {
            array_push($array_fakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }
        $data_grafik_fakultas = [
            '<= 1 Jt' => $array_fakultas,
            '<= 3 Jt' => $array_fakultas,
            '<= 5 Jt' => $array_fakultas,
            '> 5 Jt' => $array_fakultas
        ];
        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f505) && $decode_answer->f505 != 0) {
                $f505 = preg_replace('/[^0-9]/', '', $decode_answer->f505);
                switch ($f505) {
                    case $f505 <= 1000000:
                        foreach ($data_grafik_fakultas['<= 1 Jt'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['<= 1 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                    case $f505 <= 3000000:
                        foreach ($data_grafik_fakultas['<= 3 Jt'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['<= 3 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                    case $f505 <= 5000000;
                        foreach ($data_grafik_fakultas['<= 5 Jt'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['<= 5 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                    default:
                        foreach ($data_grafik_fakultas['> 5 Jt'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['> 5 Jt'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            } else {
                foreach ($data_grafik_fakultas['<= 1 Jt'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['<= 1 Jt'][$key]['y'] += 1;
                    }
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Penghasilan',
            'data_grafik_prodi' =>  $data_grafik_prodi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($data_grafik_universitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Penghasilan'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function berwirausaha() //Optimized, New,
    {
        $answer_kues = $this->answerQuestData("berwirausaha", "Berwirausaha");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $array_prodi = [];
        foreach ($prodi as $f) {
            array_push($array_prodi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }
        $data_grafik_prodi = [
            'Berwirausaha' => $array_prodi,
            'Tidak Berwirausaha' => $array_prodi,
        ];
        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8[1]) && $decode_answer->f8[1] == 3) {
                foreach ($data_grafik_prodi['Berwirausaha'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['Berwirausaha'][$key]['y'] += 1;
                    }
                }
            } else {
                foreach ($data_grafik_prodi['Tidak Berwirausaha'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['Tidak Berwirausaha'][$key]['y'] += 1;
                    }
                }
            }
        }

        //perguruantinggi
        $data_grafik_universitas = [
            0 => [
                'label' => 'Berwirausaha',
                'y' => 0
            ],
            1 => [
                'label' => 'Tidak Berwirausaha',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8[1]) && $decode_answer->f8[1] == 3) {
                $data_grafik_universitas[0]['y'] += 1;
            } else {
                $data_grafik_universitas[1]['y'] += 1;
            }
        }

        //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $array_fakultas = [];
        foreach ($fakultas as $f) {
            array_push($array_fakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }

        $data_grafik_fakultas = [
            'Berwirausaha' => $array_fakultas,
            'Tidak Berwirausaha' => $array_fakultas
        ];
        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8[1]) && $decode_answer->f8[1] == 3) {
                foreach ($data_grafik_fakultas['Berwirausaha'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Berwirausaha'][$key]['y'] += 1;
                    }
                }
            } else {
                foreach ($data_grafik_fakultas['Tidak Berwirausaha'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Tidak Berwirausaha'][$key]['y'] += 1;
                    }
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Berwirausaha',
            'data_grafik_prodi' =>  $data_grafik_prodi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($data_grafik_universitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Berwirausaha'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function lanjutstudy() //Optimized, New,
    {
        $answer_kues = $this->answerQuestData("lanjutstudy", "Lanjut Study");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $array_prodi = [];
        foreach ($prodi as $f) {
            array_push($array_prodi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }
        $data_grafik_prodi = [
            'Lanjut Study' => $array_prodi,
            'Tidak Lanjut Study' => $array_prodi
        ];
        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8[0]) && $decode_answer->f8[0] == 4) {
                foreach ($data_grafik_prodi['Lanjut Study'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['Lanjut Study'][$key]['y'] += 1;
                    }
                }
            } else {
                foreach ($data_grafik_prodi['Tidak Lanjut Study'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['Tidak Lanjut Study'][$key]['y'] += 1;
                    }
                }
            }
        }

        //perguruantinggi
        $data_grafik_universitas = [
            0 => [
                'label' => 'Lanjut Study',
                'y' => 0
            ],
            1 => [
                'label' => 'Tidak Lanjut Study',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a->answer);
            if (isset($decode_answer->f8[0]) && $decode_answer->f8[0] == 4) {
                $data_grafik_universitas[0]['y'] += 1;
            } else {
                $data_grafik_universitas[1]['y'] += 1;
            }
        }

        // //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $array_fakultas = [];
        foreach ($fakultas as $f) {
            array_push($array_fakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }

        $data_grafik_fakultas = [
            'Lanjut Study' => $array_fakultas,
            'Tidak Lanjut Study' => $array_fakultas
        ];
        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8[0]) && $decode_answer->f8[0] == 4) {
                foreach ($data_grafik_fakultas['Lanjut Study'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Lanjut Study'][$key]['y'] += 1;
                    }
                }
            } else {
                foreach ($data_grafik_fakultas['Tidak Lanjut Study'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Tidak Lanjut Study'][$key]['y'] += 1;
                    }
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Lanjut Study',
            'data_grafik_prodi' =>  $data_grafik_prodi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($data_grafik_universitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Lanjut Study'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function lulusankurangenambulan() //Optimzed, New,
    {
        $answer_kues = $this->answerQuestData("lulusankurangenambulan", "Lulus Kurang Enam Bulan");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $array_prodi = [];
        foreach ($prodi as $f) {
            array_push($array_prodi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }
        $data_grafik_prodi = [
            'Kurang Dari 6 Bulan' => $array_prodi,
            'Lebih Dari 6 Bulan' => $array_prodi
        ];
        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f504) && $decode_answer->f504 == 1) {
                foreach ($data_grafik_prodi['Kurang Dari 6 Bulan'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['Kurang Dari 6 Bulan'][$key]['y'] += 1;
                    }
                }
            } else {
                foreach ($data_grafik_prodi['Lebih Dari 6 Bulan'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $data_grafik_prodi['Lebih Dari 6 Bulan'][$key]['y'] += 1;
                    }
                }
            }
        }

        //perguruantinggi
        $data_grafik_universitas = [
            0 => [
                'label' => 'Kurang Dari 6 Bulan',
                'y' => 0
            ],
            1 => [
                'label' => 'Lebih Dari 6 Bulan',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f504)  && $decode_answer->f504 == 1) {
                $data_grafik_universitas[0]['y'] += 1;
            } else {
                $data_grafik_universitas[1]['y'] += 1;
            }
        }

        //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $array_fakultas = [];
        foreach ($fakultas as $f) {
            array_push($array_fakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }
        $data_grafik_fakultas = [
            'Kurang Dari 6 Bulan' => $array_fakultas,
            'Lebih Dari 6 Bulan' => $array_fakultas
        ];
        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f504) && $decode_answer->f504 == 1) {
                foreach ($data_grafik_fakultas['Kurang Dari 6 Bulan'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Kurang Dari 6 Bulan'][$key]['y'] += 1;
                    }
                }
            } else {
                foreach ($data_grafik_fakultas['Lebih Dari 6 Bulan'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Lebih Dari 6 Bulan'][$key]['y'] += 1;
                    }
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Lulusan <= 6 Bulan',
            'data_grafik_prodi' =>  $data_grafik_prodi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($data_grafik_universitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Lulusan <= 6 Bulan'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function status() //Optimized, New
    {
        $answer_kues = $this->answerQuestData("status", "Status");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $arrayProdi = [];

        foreach ($prodi as $f) {
            array_push($arrayProdi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }

        $dataGrafikProdi = [
            'Bekerja' => $arrayProdi,
            'Lanjut Studi' => $arrayProdi,
            'Tidak Bekerja' => $arrayProdi,
            'Wiraswasta' => $arrayProdi,
            'Belum Memungkinkan Kerja' => $arrayProdi
        ];

        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8)) {
                $f8 = $decode_answer->f8;
                switch ($f8) {
                    case isset($f8[2]) && $f8[2] == 1:
                        foreach ($dataGrafikProdi['Bekerja'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $dataGrafikProdi['Bekerja'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case isset($f8[1]) && $f8[1] == 3:
                        foreach ($dataGrafikProdi['Wiraswasta'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $dataGrafikProdi['Wiraswasta'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case $f8[0] == 4:
                        foreach ($dataGrafikProdi['Lanjut Studi'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $dataGrafikProdi['Lanjut Studi'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case isset($f8[2]) && $f8[2] == 2:
                        foreach ($dataGrafikProdi['Belum Memungkinkan Kerja'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $dataGrafikProdi['Belum Memungkinkan Kerja'][$key]['y'] += 1;
                            }
                        }
                        break;

                    default:
                        foreach ($dataGrafikProdi['Tidak Bekerja'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $dataGrafikProdi['Tidak Bekerja'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            } else {
                foreach ($dataGrafikProdi['Tidak Bekerja'] as $key => $value) {
                    if ($a['prodi'] == $value['label']) {
                        $dataGrafikProdi['Tidak Bekerja'][$key]['y'] += 1;
                    }
                }
            }
        }

        //perguruantinggi
        $dataGrafikUniversitas = [
            0 => [
                'label' => 'Bekerja',
                'y' => 0
            ],
            1 => [
                'label' => 'Lanjut Studi',
                'y' => 0
            ],
            2 => [
                'label' => 'Tidak Bekerja',
                'y' => 0
            ],
            3 => [
                'label' => 'Wiraswasta',
                'y' => 0
            ],
            4 => [
                'label' => 'Belum Memungkinkan Kerja',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8)) {
                $f8 = $decode_answer->f8;
                switch ($f8) {
                    case isset($f8[2]) && $f8[2] == 1:
                        $dataGrafikUniversitas[0]['y'] += 1;
                        break;

                    case $f8[0] == 4:
                        $dataGrafikUniversitas[1]['y'] += 1;
                        break;

                    case isset($f8[1]) && $f8[1] == 3:
                        $dataGrafikUniversitas[3]['y'] += 1;
                        break;

                    case isset($f8[2]) && $f8[2] == 2:
                        $dataGrafikUniversitas[4]['y'] += 1;
                        break;

                    default:
                        $dataGrafikUniversitas[2]['y'] += 1;
                        break;
                }
            } else {
                $dataGrafikUniversitas[2]['y'] += 1;
            }
        }

        //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $arrayFakultas = [];
        foreach ($fakultas as $f) {
            array_push($arrayFakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }

        $data_grafik_fakultas = [
            'Bekerja' => $arrayFakultas,
            'Lanjut Studi' => $arrayFakultas,
            'Tidak Bekerja' => $arrayFakultas,
            'Wiraswasta' => $arrayFakultas,
            'Belum Memungkinkan Kerja' => $arrayFakultas
        ];

        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f8)) {
                $f8 = $decode_answer->f8;
                switch ($f8) {
                    case isset($f8[2]) && $f8[2] == 1:
                        foreach ($data_grafik_fakultas['Bekerja'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Bekerja'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case isset($f8[1]) && $f8[1] == 3:
                        foreach ($data_grafik_fakultas['Wiraswasta'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Wiraswasta'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case isset($f8[2]) && $f8[2] == 2:
                        foreach ($data_grafik_fakultas['Belum Memungkinkan Kerja'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Belum Memungkinkan Kerja'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case $f8[0] == 4:
                        foreach ($data_grafik_fakultas['Lanjut Studi'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Lanjut Studi'][$key]['y'] += 1;
                            }
                        }
                        break;

                    default:
                        foreach ($data_grafik_fakultas['Tidak Bekerja'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Tidak Bekerja'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            } else {
                foreach ($data_grafik_fakultas['Tidak Bekerja'] as $key => $value) {
                    if ($a['nama_fakultas'] == $value['label']) {
                        $data_grafik_fakultas['Tidak Bekerja'][$key]['y'] += 1;
                    }
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Status',
            'data_grafik_prodi' =>  $dataGrafikProdi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($dataGrafikUniversitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Status'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function keselarasanvertical() //Optimized, New
    {
        $answer_kues = $this->answerQuestData("keselarasanvertical", "Keselarasan Vertical");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $array_prodi = [];
        foreach ($prodi as $f) {
            array_push($array_prodi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }
        $data_grafik_prodi = [
            'Tinggi' => $array_prodi,
            'Sama' => $array_prodi,
            'Rendah' => $array_prodi
        ];
        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f15)) {
                $f15 = $decode_answer->f15;
                switch ($f15) {
                    case 1:
                        foreach ($data_grafik_prodi['Tinggi'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['Tinggi'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case 2:
                        foreach ($data_grafik_prodi['Sama'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['Sama'][$key]['y'] += 1;
                            }
                        }
                        break;

                    default:
                        foreach ($data_grafik_prodi['Rendah'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['Rendah'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            }
        }

        //perguruantinggi
        $data_grafik_universitas = [
            0 => [
                'label' => 'Tinggi',
                'y' => 0
            ],
            1 => [
                'label' => 'Sama',
                'y' => 0
            ],
            2 => [
                'label' => 'Rendah',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f15)) {
                $f15 = $decode_answer->f15;
                switch ($f15) {
                    case 1:
                        $data_grafik_universitas[0]['y'] += 1;
                        break;

                    case 2:
                        $data_grafik_universitas[1]['y'] += 1;
                        break;

                    default:
                        $data_grafik_universitas[2]['y'] += 1;
                        break;
                }
            }
        }

        //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $array_fakultas = [];
        foreach ($fakultas as $f) {
            array_push($array_fakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }
        $data_grafik_fakultas = [
            'Tinggi' => $array_fakultas,
            'Sama' => $array_fakultas,
            'Rendah' => $array_fakultas
        ];
        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f15)) {
                $f15 = $decode_answer->f15;
                switch ($f15) {
                    case 1:
                        foreach ($data_grafik_fakultas['Tinggi'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Tinggi'][$key]['y'] += 1;
                            }
                        }
                        break;

                    case 2:
                        foreach ($data_grafik_fakultas['Sama'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Sama'][$key]['y'] += 1;
                            }
                        }
                        break;

                    default:
                        foreach ($data_grafik_fakultas['Rendah'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Rendah'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Keselarasan Vertical',
            'data_grafik_prodi' =>  $data_grafik_prodi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($data_grafik_universitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Keselarasan Vertical'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function keselarasanhorizontal() //Optimized, New
    {
        $answer_kues = $this->answerQuestData("keselarasanhorizontal", "Keselarasan Horizontal");

        //prodi
        $prodi = Prodi::where('prodi.id', '!=', '1')->get();
        $array_prodi = [];
        foreach ($prodi as $f) {
            array_push($array_prodi, [
                'label' => $f->prodi,
                'y' => 0
            ]);
        }
        $data_grafik_prodi = [
            'Selaras' => $array_prodi,
            'Tidak Selaras' => $array_prodi
        ];
        foreach ($answer_kues['prodi'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f14)) {
                $f14 = $decode_answer->f14;
                switch ($f14) {
                    case $f14 >= 3:
                        foreach ($data_grafik_prodi['Selaras'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['Selaras'][$key]['y'] += 1;
                            }
                        }
                        break;

                    default:
                        foreach ($data_grafik_prodi['Tidak Selaras'] as $key => $value) {
                            if ($a['prodi'] == $value['label']) {
                                $data_grafik_prodi['Tidak Selaras'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            }
        }

        //perguruantinggi
        $data_grafik_universitas = [
            0 => [
                'label' => 'Selaras',
                'y' => 0
            ],
            1 => [
                'label' => 'Tidak Selaras',
                'y' => 0
            ]
        ];
        foreach ($answer_kues['universitas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f14)) {
                $f14 = $decode_answer->f14;
                switch ($f14) {
                    case $f14 >= 3:
                        $data_grafik_universitas[0]['y'] += 1;
                        break;

                    default:
                        $data_grafik_universitas[1]['y'] += 1;
                        break;
                }
            }
        }

        //fakultas
        $fakultas = Fakultas::where('fakultas.id', '!=', '1')->get();

        $array_fakultas = [];
        foreach ($fakultas as $f) {
            array_push($array_fakultas, [
                'label' => $f->nama_fakultas,
                'y' => 0
            ]);
        }
        $data_grafik_fakultas = [
            'Selaras' => $array_fakultas,
            'Tidak Selaras' => $array_fakultas
        ];
        foreach ($answer_kues['fakultas'] as $a) {
            $decode_answer = json_decode($a['answer']);
            if (isset($decode_answer->f14)) {
                $f14 = $decode_answer->f14;
                switch ($f14) {
                    case $f14 >= 3:
                        foreach ($data_grafik_fakultas['Selaras'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Selaras'][$key]['y'] += 1;
                            }
                        }
                        break;

                    default:
                        foreach ($data_grafik_fakultas['Tidak Selaras'] as $key => $value) {
                            if ($a['nama_fakultas'] == $value['label']) {
                                $data_grafik_fakultas['Tidak Selaras'][$key]['y'] += 1;
                            }
                        }
                        break;
                }
            }
        }

        $data = [
            'user' => $answer_kues['user'],
            'title' => $answer_kues['title'],
            'title_chart' => 'Report Keselarasan Horizontal',
            'data_grafik_prodi' =>  $data_grafik_prodi,
            'data_grafik_fakultas' =>  $data_grafik_fakultas,
            'data_grafik_universitas' =>  json_encode($data_grafik_universitas, JSON_NUMERIC_CHECK),
            'menu' => 'Report',
            'submenu' => 'Keselarasan Horizontal'
        ];
        return view('admin.report.report_chart', $data);
    }

    public function userRegisterVerification()
    {
        $user           = Auth::user();
        $userFaculty    = $user->role_id == 3 ? Prodi::find($user->prodi_id)->fakultas_id : null;

        $inactiveUsers  = (new User)->getInactiveUsersAccount($userFaculty);

        $data = [
            'inactiveUsers'     => $inactiveUsers,
        ];

        return view(
            'admin.report.accreditation',
            $this->data(
                'Data Pengguna',
                'Data Pengguna',
                'Data Pengguna',
                $data
            )
        );
    }

    public function userVerification()
    {
        $user = Auth::user();

        $users = User::filter(request(['cari', 'sortByStatus']))
            ->select('users.*')
            ->join('prodi', 'prodi.id', '=', 'users.prodi_id')
            ->join('fakultas', 'fakultas.id', '=', 'prodi.fakultas_id')
            ->where('active', '=', 'inactive')
            ->orWhere([['role_id', '=', 2], ['role_id', '=', 4]]);

        if ($user->role_id == 3) {
            $users = $users
                ->where('fakultas.id', $user->prodi->fakultas->id);
        }

        $users = $users->orderBy('users.id', 'DESC')->paginate(10);

        $success_msg = [];
        if (Session::get("success")) {
            $success_msg = explode("\n", Session::get("success"));
        }

        $data = [
            'user'          => $user,
            'title'         => 'Verifikasi Pengguna',
            'menu'          => 'Verifikasi Pengguna',
            'submenu'       => 'Verifikasi Pengguna',
            'prodi'         => Prodi::all(),
            'alumni'        => $users,
            'success_msg'   => $success_msg
        ];
        return view('admin.pengguna.user_verification', $data);
    }

    public function verifiedUser(Request $request)
    {
        $user = User::find($request->input('id'));
        $user->active = 'active';
        $user->save();

        return redirect('admin/user_verification')->with('success', 'Verifikasi pengguna berhasil');
    }
}
