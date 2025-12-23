<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $nrm = null;
            $nik = $request->input('nik');
            $npwp = $request->input('npwp', '000000000000000');
            $nama = $request->input('nama');
            $no_paspor = $request->input('no_paspor', '0000000000');
            $sebutan = $request->input('sebutan');
            $tgl_lahir = $request->input('tgl_lahir');
            $tempat_lahir = $request->input('tempat_lahir');
            $jenkel = $request->input('jenis_kelamin');
            $no_hp = $request->input('no_hp', '');
            $email = $request->input('email', '');
            $alamat = $request->input('alamat', '');
            $propinsi_id = $request->input('propinsi', '');
            $nama_propinsi = DB::table('smis_rg_propinsi')->where('id', $propinsi_id)->value('nama');
            $kabupaten_id = $request->input('kabupaten', '');
            $nama_kabupaten = DB::table('smis_rg_kabupaten')->where('id', $kabupaten_id)->value('nama');
            $kecamatan_id = $request->input('kecamatan', '');
            $nama_kecamatan = DB::table('smis_rg_kec')->where('id', $kecamatan_id)->value('nama');
            $kelurahan_id = $request->input('kelurahan', '');
            $nama_kelurahan = DB::table('smis_rg_kelurahan')->where('id', $kelurahan_id)->value('nama');
            $rt = $request->input('rt', '');
            $rw = $request->input('rw', '');

            $cek_pasien = DB::table('smis_rg_patient')
                ->where('ktp', $nik)
                ->first();

            if (!$cek_pasien) {
                $data_pasien = [
                    'tanggal' => date('Y-m-d'),
                    'sebutan' => $sebutan,
                    'nama' => $nama,
                    'alamat' => $alamat,
                    'tempat_lahir' => $tempat_lahir,
                    'tgl_lahir' => $tgl_lahir,
                    'tgl_lahir' => $tgl_lahir,
                    'status' => '',
                    'kelamin' => $jenkel,
                    'ktp' => $nik,
                    'npwp' => $npwp,
                    'no_paspor' => $no_paspor,
                    'rt' => $rt,
                    'rw' => $rw,
                    'provinsi' => $propinsi_id,
                    'nama_provinsi' => $nama_propinsi,
                    'kabupaten' => $kabupaten_id,
                    'nama_kabupaten' => $nama_kabupaten,
                    'kecamatan' => $kecamatan_id,
                    'nama_kecamatan' => $nama_kecamatan,
                    'kelurahan' => $kelurahan_id,
                    'nama_kelurahan' => $nama_kelurahan,
                    'telpon' => $no_hp,
                    'email' => $email,
                    'kedusunan' => 0,
                    'umur' => 0,
                    'kartu' => 0,
                    'id_karyawan' => 0,
                    'synch' => 0,
                    'autonomous' => '[rsij]',
                    'duplicate' => 0,
                    'origin' => 'rsij',
                    'origin_id' => 0,
                    'time_updated' => date('Y-m-d H:i:s'),
                    'origin_updated' => 'rsij',
                ];

                $nrm = DB::table('smis_rg_patient')->insertGetId($data_pasien);
            } else {
                $nrm = $cek_pasien->id;
            }

            // data smis layanan pasien
            $data_rg_layananpasien = [];
            $baru_lama = DB::table('smis_rg_layananpasien')
                ->where('nrm', $nrm)->count() > 0 ? 1 : 0;
            $no_urut = DB::table('smis_rg_layananpasien')
                ->where('jenislayanan', 'poli_vaksin')
                ->whereDate('tanggal', date('Y-m-d'))
                ->max('no_urut');
            $no_urut = $no_urut ? $no_urut + 1 : 1;
            $no_kunjungan = DB::table('smis_rg_layananpasien')->where('nrm', $nrm)->count() + 1;
            $ceklast_kunjungan = DB::table('smis_rg_layananpasien')
                ->where('nrm', $nrm)
                ->orderBy('id', 'desc')
                ->first();
            if ($ceklast_kunjungan) {
                $last_ruangan = $ceklast_kunjungan->last_ruangan;
            } else {
                $last_ruangan = 'poli_vaksin';
            }

            $data_rg_layananpasien = [
                'tb_lainya' => 0,
                'alodokter' => 0,
                'operator_pulang_aktifkan' => '',
                'time_pulang_aktifkan' => '',
                'operator_locker' => '',
                'time_locker' => '',
                'paket' => 0,
                'kelas_kamar' => '',
                'carapulang' => '',
                'jenispasien' => '',
                'rujukan' => '',
                'nama_rujukan' => '',
                'nama_perusahaan' => '',
                'asuransi' => 0,
                'nobpjs' => '',
                'ppk_bpjs' => '',
                'bb' => '',
                'no_urut' => $no_urut,
                'no_kunjungan' => $no_kunjungan,
                'barulama' => $baru_lama,
                'tanggal' => date('Y-m-d H:i:s'),
                'alamat_pasien' => $alamat,
                'nama_kelurahan' => $nama_kelurahan,
                'nama_kecamatan' => $nama_kecamatan,
                'nama_kabupaten' => $nama_kabupaten,
                'nama_provinsi' => $nama_propinsi,
                'nama_pasien' => $nama,
                'kelamin' => $jenkel,
                'nrm' => $nrm,
                'jenislayanan' => 'poli_vaksin',
                'karcis' => 25000,
                'lunas' => 0,
                'carabayar' => 'umum',
                'caradatang' => 'Datang Sendiri',
                'id_rujukan' => 0,
                'plafon_bpjs' => 0,
                'kunci_bpjs' => 0,
                'plafon_bpjs' => 0,
                'status_bpjs' => 0,
                'diagnosa_bpjs' => '',
                'tindakan_bpjs' => '',
                'keterangan_bpjs' => '',
                'inacbg_bpjs' => '',
                'keterangan_bpjs' => '',
                'deskripsi_bpjs' => '',
                'plafon_naik_kelas' => 0,
                'naik_kelas_bpjs' => '',
                'kelas_bpjs' => '',
                'periode_bpjs' => '',
                'keteranganbonus' => '',
                'besarbonus' => 0,
                'ambilbonus' => '',
                'besarambil' => 0,
                'kodebonus' => 0,
                'namakodebonus' => '',
                'namapenanggungjawab' => '',
                'telponpenanggungjawab' => '',
                'alamat_pj' => '',
                'desa_pj' => '',
                'kecamatan_pj' => '',
                'kabupaten_pj' => '',
                'pekerjaan_pj' => '',
                'umur_pj' => '',
                'hubungan_pj' => '',
                'selesai' => 0,
                'umur' => $this->hitungumur($tgl_lahir)['terbilang'],
                'gol_umur' => $this->hitungumur($tgl_lahir)['golongan'],
                'uri' => 0,
                'history_dokter' => '',
                'kamar_inap' => '',
                'status_dokumen' => '',
                'administrasi_inap' => 0,
                'gratis' => 0,
                'opsi_gratis' => '',
                'alasan_gratis' => '',
                'total_tagihan' => 0,
                'total_bayar' => 0,
                'total_paket' => 0,
                'rekam_medis' => '',
                'tb_asuransi' => 0,
                'tb_bank' => 0,
                'tb_cash' => 0,
                'tb_diskon' => 0,
                'synch' => 0,
                'akunting' => 0,
                'id_bed_kamar' => 0,
                'id_dokter' => '',
                'nama_dokter' => '',
                'tanggal_inap' => `0000-00-00 00:00:00`,
                'tanggal_pulang' => `0000-00-00 00:00:00`,
                'last_ruangan' => $last_ruangan,
                'last_nama_ruangan' => ucwords(str_replace('_', ' ', $last_ruangan)),
                'last_spesialisasi' => '',
                'last_spesialisasi2' => '',
                'jenis_kegiatan' => '',
                'last_edit_username' => '',
                'diskon_keterangan' => '',
                'last_kelas' => 'non_kelas',
                'no_sep_rj' => '',
                'no_sep_ri' => '',
                'opri' => '',
                'oprj' => '',
                'opp' => '',
                'last_bed' => '',
                'obs' => 0,
                'hbi' => '',
                'bed_kamar' => '',
                'lama_dirawat' => 0,
                'tutup_tagihan' => 0,
                'duplicate' => 0,
                'autonomous' => '[rsij]',
                'origin' => 'rsij',
                'origin_id' => 0,
                'time_updated' => date('Y-m-d H:i:s'),
                'akunting_notify_date' => `0000-00-00 00:00:00`,
                'origin_updated' => 'rsij',
                'last_edit_timestamp' => date('Y-m-d H:i:s'),
            ];
            $smis_rg_layananpasien_id = DB::table('smis_rg_layananpasien')->insertGetId($data_rg_layananpasien);


            // data smis rwt in log poli vaksin
            $data_rwt_in_log_poli_vaksin = [
                'nama_pasien' => $nama,
                'noreg_pasien' => $smis_rg_layananpasien_id,
                'nrm_pasien' => $nrm,
                'tanggal' => date('Y-m-d H:i:s'),
                'petugas' => 'Test API',
                'asal' => 'poli_vaksin',
                'keterangan' => 'Pendaftaran via Online',
                'duplicate' => 0,
                'autonomous' => '',
                'origin' => '',
                'origin_id' => 0,
                'time_updated' => date('Y-m-d H:i:s'),
                'origin_updated' => '',
            ];
            DB::table('smis_rwt_in_log_poli_vaksin')->insert($data_rwt_in_log_poli_vaksin);


            // data smis rwt antrian poli vaksin
            $cek_antrian_per_day = DB::table('smis_rwt_antrian_poli_vaksin')
                ->whereDate('waktu', date('Y-m-d'))
                ->count();

            if ($cek_antrian_per_day == 0) {
                $no_antrian = 1;
            } else {
                $no_antrian = DB::table('smis_rwt_antrian_poli_vaksin')
                    ->whereDate('waktu', date('Y-m-d'))
                    ->max('nomor') + 1;
            }
            $json_detail = [
                'alamat' => $alamat,
                'ibu' => '',
                'caradatang' => 'Datang Sendiri',
                'waktu_register' => date('Y-m-d H:i:s'),
                'alodokter' => 0,
                'tgl_lahir' => $tgl_lahir,
                'no_profile' => '',
            ];
            $data_rwt_antrian_poli_vaksin = [
                'waktu_register' => date('Y-m-d H:i:s'),
                'waktu' => date('Y-m-d H:i:s'),
                'no_register' => $smis_rg_layananpasien_id,
                'selesai' => 0,
                'kunjungan' => $baru_lama == 0 ? 'Baru' : 'Lama',
                'nama_pasien' => $nama,
                'jk' => $jenkel,
                'carabayar' => 'umum',
                'nrm_pasien' => $nrm,
                'nomor' => $no_antrian,
                'umur' => $this->hitungumur($tgl_lahir)['terbilang'],
                'golongan_umur' => $this->hitungumur($tgl_lahir)['golongan'],
                'duplicate' => 0,
                'asal' => 'Pendaftaran',
                'kelas' => 'non_kelas',
                'cara_keluar' => '',
                'waktu_keluar' => `0000-00-00 00:00:00`,
                'keterangan_keluar' => '',
                'id_rs_rujuk' => 0,
                'nama_rs' => '',
                'nama_unit' => '',
                'is_spesialistik' => 0,
                'detail' => json_encode($json_detail),
                'ibu_kandung' => '',
                'rl52' => 'Umum',
                'dokumen' => '',
                'alamat' => $alamat,
                'titipan' => 0,
                'room_name' => '',
                'state_name' => '',
                'status_pasien' => '',
                'keterangan_pindah' => '',
                'lama_rawat' => 0,
                'ruang_tujuan' => '',
                'dipulangkan_kasir' => 0,
                'spesialisasi' => '',
                'spesialisasi2' => '',
                'autonomous' => '[rsij]',
                'duplicate' => 0,
                'origin' => 'rsij',
                'time_updated' => date('Y-m-d H:i:s'),
                'origin_id' => 0,
                'origin_updated' => 'rsij',
            ];
            $smis_rwt_antrian_poli_vaksin = DB::table('smis_rwt_antrian_poli_vaksin')->insertGetId($data_rwt_antrian_poli_vaksin);
            DB::table('smis_rwt_antrian_poli_vaksin')->where('id', $smis_rwt_antrian_poli_vaksin)->update(['origin_id' => $smis_rwt_antrian_poli_vaksin]);

            // data smis ksr kolektif
            $data_ksr_kolektif = [
                'jenis_keperawatan' => 0,
                'perusahaan' => '',
                'asuransi' => '',
                'id_tagihan' => 0,
                'guid_paket' => '',
                'paket' => 0,
                'idgrup' => 0,
                'urutan' => 1,
                'noreg_pasien' => $smis_rg_layananpasien_id,
                'nrm_pasien' => $nrm,
                'nama_pasien' => $nama,
                'id_unit' => $smis_rg_layananpasien_id,
                'nama_grup' => 'registration',
                'jenis_tagihan' => 'registration',
                'nama_tagihan' => 'Karcis Pendaftaran Pasien (POLI_VAKSIN)',
                'ruangan' => 'registration',
                'ruangan_map' => 'PENDAFTARAN',
                'keterangan' => 'Karcis poli_vaksin Senilai  Rp. 25.000,00 Belum dibayar',
                'tanggal' => \Carbon\Carbon::now()->format('d F Y'),
                'quantity' => 1,
                'nilai' => 25000,
                'jaspel_by' => 0,
                'jaspel' => 0,
                'total' => 25000,
                'hidden' => 0,
                'nilai_by' => 25000,
                'nama_by' => 'Karcis Pendaftaran Pasien (POLI_VAKSIN)',
                'ruang_by' => 'registration',
                'kelas_by' => '',
                'status' => 0,
                'dari' => date('Y-m-d H:i:s'),
                'sampai' => date('Y-m-d H:i:s'),
                'ruangan_kasir' => 'registration',
                'id_kwitansi' => 0,
                'akunting' => 0,
                'debet' => '',
                'kredit' => '',
                'akunting' => 0,
                'akunting_nama' => 'Karcis Pendaftaran Pasien (POLI_VAKSIN)',
                'akunting_only' => 0,
                'akunting_nilai' => 25000,
                'akunting_posted' => `0000-00-00 00:00:00`,
                'nama_dokter' => '',
                'id_dokter' => 0,
                'jaspel_persen' => 0,
                'jaspel_dokter' => 0,
                'urjigd' => 'urj',
                'carabayar' => 'umum',
                'tanggal_masuk' => date('Y-m-d'),
                'tanggal_pulang' => date('Y-m-d'),
                'tanggal_tagihan' => `0000-00-00`,
                'tanggal_pulang' => `0000-00-00`,
                'selesai' => 0,
                'tutup_tagihan' => 1,
            ];
            DB::table('smis_ksr_kolektif')->insert($data_ksr_kolektif);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil',
                'data' => [
                    'nrm' => $nrm,
                    'no_kunjungan' => $no_kunjungan,
                    'no_urut' => $no_urut,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Pendaftaran gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function hitungumur($tanggal_lahir)
    {
        $tglLahir = date_create($tanggal_lahir);
        $today = date_create('today');

        $diff = date_diff($tglLahir, $today);

        $tahun = $diff->y;
        $bulan = $diff->m;
        $hari = $diff->d;

        $umurTerbilang = sprintf("%d Tahun %d Bulan %d Hari", $tahun, $bulan, $hari);

        $golongan = "";
        if ($tahun >= 0 && $tahun <= 4) {
            $golongan = "0-4 TH";
        } elseif ($tahun >= 5 && $tahun <= 14) {
            $golongan = "5-14 TH";
        } elseif ($tahun >= 15 && $tahun <= 24) {
            $golongan = "15-24 TH";
        } elseif ($tahun >= 25 && $tahun <= 44) {
            $golongan = "25-44 TH";
        } elseif ($tahun >= 45 && $tahun <= 59) {
            $golongan = "45-59 TH";
        } elseif ($tahun >= 60 && $tahun <= 64) {
            $golongan = "60-64 TH";
        } else {
            $golongan = "65> TH";
        }

        return [
            'terbilang' => $umurTerbilang,
            'golongan' => $golongan
        ];
    }
}
