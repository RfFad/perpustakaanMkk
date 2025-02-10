<?php include '../../layout/header.php' ?>
    <style>

        .container {
            width: 100%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #35424a;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
        }
        .profile, .contact {
            background: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #35424a;
        }
        .profile ul {
            list-style-type: disc;
            margin-left: 20px;
        }
    </style>
    <div class="container-fluid">
    <div class="container">
        <header>
            <h1>Profil Sekolah XYZ</h1>
        </header>
        <section class="profile">
            <h2>Tentang Sekolah</h2>
            <p>Sekolah XYZ didirikan pada tahun 2000 dan telah berkomitmen untuk memberikan pendidikan berkualitas kepada siswa-siswi.</p>
            <h3>Visi</h3>
            <p>Menjadi sekolah unggulan yang mencetak generasi yang berakhlak mulia dan berprestasi.</p>
            <h3>Misi</h3>
            <ul>
                <li>Menyediakan pendidikan yang berkualitas.</li>
                <li>Mendorong siswa untuk berprestasi di bidang akademik dan non-akademik.</li>
                <li>Membangun karakter siswa yang baik.</li>
            </ul>
        </section>
        <section class="contact">
            <h2>Kontak Kami</h2>
            <p>Email: info@sekolahxyz.com</p>
            <p>Telepon: (021) 12345678</p>
            <p>Alamat: Jl. Pendidikan No. 1, Jakarta</p>
        </section>
    </div>
    </div>
    <?php include '../../layout/footer.php' ?>