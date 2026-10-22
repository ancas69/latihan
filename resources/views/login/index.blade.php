@extends('templates.master_nav')

@section('content')
    <h2>Halo user, selamat datang , silahkan melakukan login ke sistem</h2>
    <form id="formLogin">
        <input name="email_l" type="email"><br>
        <input name="password_l" type="password"><br>

        <button id="kirim_l">login</button>

    </form>

    <script>
        const token = localStorage.getItem('token');
        btn = document.getElementById('kirim_l');
        let isLogin = false;

        document.getElementById('formLogin').addEventListener('submit', async (e) => {
            e.preventDefault();

            if (isLogin) return;

            isLogin = true
            btn.disabled = true;

            const form = document.getElementById('formLogin');

            const data = {
                'email': form.email_l.value,
                'password': form.password_l.value
            }
            try {
                const res = await fetch('/api/login', {
                    'method': 'post',
                    'headers': {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`

                    },
                    'body': JSON.stringify(data)
                })

                const json = await res.json();


                if (res.ok) {
                    if (!json.token) {
                        alert(json.msg);
                    } else {
                        alert(json.msg);
                        localStorage.setItem('token', json.token);
                        location.href = '/hello';
                    }

                } else {
                    alert("terjadi kesalahan pada sistem api");
                }
            } catch (error) {
                alert(error.message);
            } finally {
                btn.disabled = false;
                isLogin = false;
            }


        })
    </script>
@endsection
