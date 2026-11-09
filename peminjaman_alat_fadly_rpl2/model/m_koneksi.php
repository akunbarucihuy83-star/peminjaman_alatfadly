<?php

class m_koneksi
{
    private $host = "127.0.0.1";
    private $username = "root";
    private $pass = "root";
    private $db = "peminjaman_alat";

    public $koneksi;

    public function __construct()
    {
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->pass,
            $this->db
        );

        if (!$this->koneksi) {

            die(
                "Koneksi database gagal: "
                . mysqli_connect_error()
            );

        }
    }
}

$koneksi = new m_koneksi();

?>