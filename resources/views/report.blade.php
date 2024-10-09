@extends('layouts.mainlayoutsuser')

@section('title', 'Laporan')

@section('content')

<div class="card-body">
    <div>
        <div class="mb-3">
            <div class="row">
                <div class="col-6">
                        <form action="">
                        <div>
                            <select name="Pilihan" id="">
                                <option value="desc">Tanggal Secara Descending</option>
                                <option value="asc">Tanggal Secara Ascending</option>
                            </select>
                            <input type="submit" value="Submit">
                        </div>
                    </form>
                </div>

                
                
                <div class="col-6 d-flex justify-content-end">
                    <form action="">
                        <label for="search" class="me-3 fw-bold">Cari : </label>
                        <input type="text" name="search" style="border-radius: 5px;">
                        <button hidden type="submit"></button>
                        
                    </form>
                </div>

            </div>
                <div class="mt-3">
                    <form action="report/export/excel">

                    @if(isset($search)) 
                    <input hidden type="" name="search" value="{{$search}}"> 
                    @else
                    <input hidden type="" name="Pilihan" value="{{$option}}">
                    @endif
                        <button class="btn btn-success" type="submit" style="border-radius: 0;" ><i class="bi bi-filetype-csv"></i> CSV</button>
                    </form>
                </div>

        </div>

        @include('reporttable') 
        
        {{ $transactions->links('pagination::paginator-bengkel') }}
    </div>
</div>

@endsection