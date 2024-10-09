@extends('layouts.mainlayoutsadmin')

@section('title', 'Laporan')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Modal Notifikasi Periode Laporan-->
    <div class="modal fade" id="reportPeriodModal" tabindex="-1" aria-labelledby="reportPeriodModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="periodReportModalLabel">Periode Laporan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <form action="">

            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <label for="datepickerStart" class="form-label">Tanggal Awal</label>
                        <input type="text" class="form-control" name="startDate" id="datepickerStart">
                        
                    </div>
                    <div class="col-6">
                        <label for="datepickerEnd" class="form-label">Tanggal Akhir</label>
                        <input type="text" class="form-control" name="endDate" id="datepickerEnd">
                    </div>

                </div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Simpan</button>
                
            </div>
            </form>

        
        </div>
    </div>
    </div>

    <div class="row">
        
        <div class="col-sm-6 my-auto">
            <button class="btn btn-navySmall" data-bs-toggle="modal" data-bs-target="#reportPeriodModal">Ubah Periode</button>
        </div>
        
        <div class="sideBar d-flex justify-content-end col-sm-6 my-auto">
            <form action="" id="pagination-form">

                <select name="paginate" id="paginate" onchange="submitForm()">
                    <option value="">Kuantitas</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <button hidden type="submit" >Hello</button>
            </form>
        </div>

    </div>
    
                <div class="mt-3">
                    <form action="reportIncome/export/excel">
                        @if(isset($startDate) && isset($endDate)) 
                        <input hidden type="" name="startDate" value="{{$startDate}}">
                        <input hidden type="" name="endDate" value="{{$endDate}}">
                        @elseif(isset($paginate))
                        <input hidden type="" name="paginate" value="{{$paginate}}">
                        @endif
                        <button class="btn btn-success" type="submit" style="border-radius: 0;" ><i class="bi bi-filetype-csv"></i> CSV</button>
                    </form> 
                </div>
    
        @include('adminproducttable')

        <!-- paginator -->
    {{ $report->links('pagination::paginator-bengkel') }}
    </div>

    <script src="scripts.js"></script>

    <script>
        var modal = document.getElementById('notificationModal');
        var span = document.getElementsByClassName('close-button')[0];

        span.onclick = function() {
            modal.style.display = 'none';
        }

        function openNotification() {
            modal.style.display = 'block';

            setTimeout(function() {
                modal.style.display = 'none';
            }, 2000); 
        }

    </script>
<script>
    $(function() {
        $("#datepickerStart").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>

<script>
    $(function() {
        $("#datepickerEnd").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>

    <script>
        function submitForm() {
            document.getElementById('pagination-form').submit();
        }
    </script>



@endsection