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
                    {data: 'price', name: 'price', class: 'text-center'},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable:false, width: "25%"}
                ],
                })
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('dashboard.product.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg"
                style="background-color: rgb(44, 109, 196)">
                + Create Product</a>
            </div>
            <div class="shadow overflow-hidden sm-rounded-md mt-6">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Harga</th>
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
