@extends('templates.master_nav')

@section('content')
    <div style="padding: 20px; font-family: sans-serif;">
        <h2 style="margin-bottom: 20px;">masukkan data</h2>

        <form id="formUser">
            {{-- @csrf --}}

            <span> masukkan nama : </span>
            <input name="name" type="text">
            <span style="color:rgb(251, 1, 1); display:none" id="nama_a">X</span>
            <br>

            <span> masukkan email : </span>
            <input name="email" type="email">
            <span style="color:rgb(251, 1, 1); display:none" id="email_a">X</span>
            <br>

            <span> masukkan password : </span>
            <input name="password" type="password">
            <span style="color:rgb(251, 1, 1); display:none" id="password_a">X</span>

            <br>
            <div id="kotak_tombol"></div>

            <p id="msg_f" style="color:rgb(251, 1, 1); display:none">ada yang salah, uhuy</p><br>

        </form>


    </div>

    <div style="padding: 20px; font-family: sans-serif;">
        <h2 style="margin-bottom: 20px;">list data</h2>

        <table border="1">
            <thead>
                <tr>
                    <td>no</td>
                    <td>nama</td>
                    <td>email</td>
                    <td colspan="2">aksi</td>
                </tr>
            </thead>

            <tbody id="daftarUsers"></tbody>
        </table>

        <button id="tombol_logout">logout</button>

    </div>

    <script>
        let token = localStorage.getItem('token');
        let idEdit = null;

        const alert_form = document.getElementById('msg_f');
        const nama_a = document.getElementById('nama_a');
        const email_a = document.getElementById('email_a');
        const password_a = document.getElementById('password_a');


        document.getElementById('tombol_logout').addEventListener('click', async (e) => {
            e.preventDefault();
            security();

            const res = await fetch('/api/logout', {
                'method': 'post',
                'headers': {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            const json = await res.json();

            if (res.ok) {
                token = null;
                location.href = '/';
            }
        })

        document.getElementById('formUser').addEventListener('submit', async (e) => {
            e.preventDefault();
            security();

            console.log('kekekekekek');

            form = document.getElementById('formUser');
            data = {
                name: form.name.value,
                email: form.email.value,
                password: form.password.value
            }

            try {
                if (idEdit === null) {
                    url = 'api/users';
                    method = 'POST';
                } else {
                    url = 'api/users/' + idEdit;
                    method = 'PUT';
                }
                res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });
                const json = await res.json();



                if (res.ok) {
                    //kasih tahu berhasil
                    alert_form.style.display = 'none';

                    nama_a.style.display = 'none';
                    email_a.style.display = 'none';
                    password_a.style.display = 'none';
                    alert(json.msg);
                    form.reset(); // kosongkan form
                    idEdit = null;
                    loadUsers();
                } else {
                    console.log(json);
                    alert_form.style.display = 'inline';

                    if (json.errors.name) nama_a.style.display = 'inline';
                    else nama_a.style.display = 'none';

                    if (json.errors.email) email_a.style.display = 'inline';
                    else email_a.style.display = 'none';

                    if (json.errors.password) password_a.style.display = 'inline';
                    else password_a.style.display = 'none';

                    alert_form.innerHTML = json.message;
                }



            } catch (error) {
                alert('error : ' + error.message)
            } finally {

            }
        })

        async function edit(id) {
            security();
            idEdit = id;
            document.getElementById('kotak_tombol').innerHTML =
                '<button onclick="cancel()">cancel</button><button type="submit">simpan</button>';

            const res = await fetch('/api/users/' + id, {
                method: 'GET',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            })

            const json = await res.json();
            const satu_data = json.data || [];

            if (satu_data.length === 0) {
                //data kosong
            } else {
                form = document.getElementById('formUser')
                form.name.value = satu_data.name
                form.email.value = satu_data.email
            }

        }



        function cancel() {
            security();
            document.getElementById('kotak_tombol').innerHTML = '<button type="submit">kirim</button>';
            document.getElementById('formUser').reset();
            idEdit = null;
        }


        async function loadUsers() {
            security();
            document.getElementById('kotak_tombol').innerHTML = '<button type="submit">kirim</button>';
            tbody = document.getElementById('daftarUsers');
            tbody.innerHTML = '<tr><td colspan="3"> sedang memuat data </td></tr>';

            try {
                const res = await fetch('/api/users', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const json = await res.json();
                const users = json.data || [];


                if (users.length === 0) {

                    tbody.innerHTML = '<tr><td colspan="3"> tidak ada data dalam database</td></tr>';

                } else {
                    tbody.innerHTML = users.map(users => `
                        <tr>
                            <td id="">${users.id}</td>
                            <td>${users.name}</td>
                            <td>${users.email}</td>
                            <td><button onclick="edit(${users.id})">edit</button></td>
                            <td><button class="tombol_hapus" data-id="${users.id}">delete</button></td>
                        </tr>
                    `).join('');



                    document.querySelectorAll('.tombol_hapus').forEach(btn => {
                        btn.addEventListener('click', async (e) => {
                            e.preventDefault();

                            const id = e.currentTarget.dataset.id;
                            try {
                                const res = await fetch('/api/users/' + id, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': `Bearer ${token}`
                                    }
                                })

                                const json = await res.json();

                                alert(json.msg);
                                loadUsers()
                            } catch (error) {
                                alert('error : ' + error.message);
                            }






                        })
                    })
                }



            } catch (error) {
                alert('error : ' + error.message);
            }



        }

        function security() {
            if (!token) {
                alert("hey you, who are you, what are you trying to do here ?");
                return location.href = '/';
            }
        }




        loadUsers();
    </script>
@endsection
