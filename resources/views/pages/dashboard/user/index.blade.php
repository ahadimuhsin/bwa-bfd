<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            //DataTable
            let userTable = $("#userTable").DataTable({
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
                    {data: 'email', name: 'email', class: 'text-center'},
                    {data: 'role', name: 'role', class: 'text-center'},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable:false, width: "25%"}
                ],
                })
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden sm-rounded-md mt-6">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="userTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </>
        </div>
    </div>
</x-app-layout>
