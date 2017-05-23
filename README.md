# GoPrayAPI

[URL Routing]

[User/Anak]
# GET User detail
/users/self/?access_token={AT}

# GET Timeline
/users/self/timeline?access_token={AT}

# GET Pesan
/users/self/pesan?access_token={AT}

# GET Kerabat
/users/self/kerabat?access_token={AT}

# POST Login
=> email
=> password
/users/self/login

# POST Daftar
=> nama
=> email
=> password
/users/self/daftar

# POST Kerabat
=> access_token
=> email
/users/self/kerabat

# POST Timeline
=> access_token
=> id_aktivitas
=> id_ibadah
=> tempat
=> bersama
=> gambar
=> point
=> tanggal
=> jam

# POST Meme
=> access_token
=> text
=> gambar (optional)

[Master]
# GET Sholat
/master/sholat?access_token={AT}

# GET Puasa
/master/puasa?access_token={AT}

# GET Doa
/master/doa?access_token={AT}

# GET Aktivitas
/master/aktivitas?access_token={AT}

# GET Jadwal Sholat
/master/aktivitas?access_token={AT}&timezone={tz}

# GET Stiker
/master/stiker?access_token={AT}

# GET Paket Stiker
/master/paketstiker?access_token={AT}

# POST JadwalSholat (Sinkron)
=> method (monthly / yearly)

/master/jadwalstiker

[Orang Tua]
# GET Timeline
/users/parent/timeline?access_token={AT}

# on Building

Code By reksarw@gmail.com
