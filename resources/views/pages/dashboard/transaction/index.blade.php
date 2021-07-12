<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            //DataTable
            let productTable = $("#productTable").DataTable({
                ajax: {
                    url: '{!! url()->current() !!}'
                },
                columns: [
                    {
                        data: "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        width :'5%'
                    },
                    {data: 'name', name: 'name', class: 'text-center'},
                    {data: 'phone', name: 'phone', class: 'text-center'},
                    {data: 'courier', name: 'courier', class: 'text-center'},
                    {data: 'total_price', name: 'total_price', class: 'text-center'},
                    {data: 'status', name: 'status', class: 'text-center'},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable:false, width: "25%"}
                ],
                })
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden sm-rounded-md mt-6">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Kurir</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
