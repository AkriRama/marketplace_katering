<table class="mt-3 table table-bordered table-hover shadow">
    <thead class="table-dark" style="vertical-align: middle;">
        <tr>
            <th>NO</th>
            <!-- Tanggal -->
            <form action="">
                    <input hidden name="startDate" id="datepickerStart">
                    <input hidden name="endDate" id="datepickerEnd">
                
                    @if(session('sortdown'))
                        <input hidden name="sortdown" value="desc">

                        <th>Tanggal <button class="btn" href=""><i class="bi bi-sort-down"></i></button></th>
                    @else

                        <input hidden name="sortup" value="asc">
                        <th>Tanggal <button class="btn" href=""><i class="bi bi-sort-up"></i></button></th>
                    @endif
                </form>
                <!-- Penjualan -->
                <form action="">

                    @if(session('sortdownSales'))
                        <input hidden name="sortdownSales" value="desc">
                        <th>Penjualan <button class="btn" href=""><i class="bi bi-sort-down"></i></button></th>
                    @else
                        <input hidden name="sortupSales" value="asc">
                        <th>Penjualan <button class="btn" href=""><i class="bi bi-sort-up"></i></button></th>
                    @endif
                </form>
                <!-- Pembelian -->
                <form action="">

                    @if(session('sortdownPurchases'))
                        <input hidden name="sortdownPurchases" value="desc">
                        <th>Pembelian <button class="btn" href=""><i class="bi bi-sort-down"></i></button></th>
                    @else
                        <input hidden name="sortupPurchases" value="asc">
                        <th>Pembelian <button class="btn" href=""><i class="bi bi-sort-up"></i></button></th>
                    @endif
                </form>
                <!-- Pengeluaran -->
                <form action="">

                    @if(session('sortdownExpenses'))
                        <input hidden name="sortdownExpenses" value="desc">
                        <th>Pengeluaran <button class="btn" href=""><i class="bi bi-sort-down"></i></button></th>
                    @else
                        <input hidden name="sortupExpenses" value="asc">
                        <th>Pengeluaran <button class="btn" href=""><i class="bi bi-sort-up"></i></button></th>
                    @endif
                </form>
                <!-- Pendapatan -->
                <form action="">

                    @if(session('sortdownIncome'))
                        <input hidden name="sortdownIncome" value="desc">
                        <th>Pendapatan <button class="btn" href=""><i class="bi bi-sort-down"></i></button></th>
                    @else
                        <input hidden name="sortupIncome" value="asc">
                        <th>Pendapatan <button class="btn" href=""><i class="bi bi-sort-up"></i></button></th>
                    @endif
                </form>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item->date}}</td>
                <td>{{number_format($item->sales, 0, ',', '.')}}</td>
                <td>{{number_format($item->purchases, 0, ',', '.')}}</td>
                <td>{{number_format($item->expenses, 0, ',', '.')}}</td>
                <td>{{number_format($item->income, 0, ',', '.')}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>