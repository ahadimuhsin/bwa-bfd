<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product &raquo; {{ $product->name }} &raquo; Gallery
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            //DataTable
            let galleryTable = $("#galleryTable").DataTable({
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
                    {data: 'url', name: 'url', class: 'text-center'},
                    {data: 'is_featured', name: 'is_featured', class: 'text-center'},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable:false, width: "25%"}
                ],
                })
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('dashboard.product.gallery.create', $product->slug) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg"
                style="background-color: rgb(44, 109, 196)">
                + Upload Photo(s)</a>
            </div>
            <div class="shadow overflow-hidden sm-rounded-md mt-6">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="galleryTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Featured</th>
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
