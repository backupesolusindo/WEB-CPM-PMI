
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelRiwayatPekerjaan extends CI_Model
{

    private $table = 'riwayat_pekerjaan';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_riwayatpekerjaan' => $id])->row();
    }



    // public function insert($data)
    // {
    //     return $this->db->insert($this->table, $data);
    // }
    public function is_duplicate_today($pekerjaan_id, $pegawai_id)
    {
        $this->db->where('pekerjaan_idpekerjaan', $pekerjaan_id);
        $this->db->where('pegawai_idpegawai', $pegawai_id);
        $this->db->where('MONTH(created_at)', date('m')); // Cek bulan
        $this->db->where('DAY(created_at)', date('d'));   // Cek tanggal
        return $this->db->count_all_results('riwayat_pekerjaan') > 0;
    }

    public function insert($data)
    {
        return $this->db->insert('riwayat_pekerjaan', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_riwayatpekerjaan', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_riwayatpekerjaan', $id);
        return $this->db->delete($this->table);
    }


    public function get_all_except_pending()
    {
        $this->db->select('
        riwayat_pekerjaan.*, 
        pekerjaan.nama_pekerjaan, 
        pekerjaan.jabatan_idjabatan, 
        pekerjaan.point,
        DATE(riwayat_pekerjaan.created_at) as tanggal,
        (riwayat_pekerjaan.jumlah * pekerjaan.point) as total_point,
        pegawai.nama_pegawai
    ');
        $this->db->from('riwayat_pekerjaan');
        $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'left');
        $this->db->join('pegawai', 'pegawai.uuid = riwayat_pekerjaan.pegawai_idpegawai');
        $this->db->where_not_in('riwayat_pekerjaan.status', ['Pending']);
        $query = $this->db->get();

        return $query->result_array();
    }
    public function update_status($id_riwayatpekerjaan, $status)
    {
        $data = ['status' => $status];
        $this->db->where('id_riwayatpekerjaan', $id_riwayatpekerjaan);
        return $this->db->update('riwayat_pekerjaan', $data);
    }
    public function search($jabatan, $start, $end)
    {
        $this->db->select('
            riwayat_pekerjaan.*,
            pekerjaan.nama_pekerjaan,
            pekerjaan.jabatan_idjabatan,
            pekerjaan.point, 
            DATE(riwayat_pekerjaan.created_at) as tanggal, 
            (riwayat_pekerjaan.jumlah * pekerjaan.point) as total_point,
            pegawai.nama_pegawai
        ');
        $this->db->from('riwayat_pekerjaan');
        $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'left');
        $this->db->join('pegawai', 'pegawai.uuid = riwayat_pekerjaan.pegawai_idpegawai', 'left');

        // Filter berdasarkan jabatan
        if (!empty($jabatan)) {
            $this->db->where('pekerjaan.jabatan_idjabatan', $jabatan);
        }

        // Filter berdasarkan tanggal (created_at)
        if (!empty($start) && !empty($end)) {
            $this->db->where('riwayat_pekerjaan.created_at >=', $start);
            $this->db->where('riwayat_pekerjaan.created_at <=', $end);
        }

        // Hanya tampilkan data yang statusnya bukan "Pending"
        $this->db->where_not_in('riwayat_pekerjaan.status', ['Pending']);

        $query = $this->db->get();
        return $query->result_array();
    }
    public function auto_reject_old_pending_tasks()
{
    $oneDayAgo = date('Y-m-d H:i:s', strtotime('-1 day'));
    
    // Find all pending tasks older than 1 day
    $this->db->where('status', 'Pending');
    $this->db->where('created_at <', $oneDayAgo);
    $pendingTasks = $this->db->get($this->table)->result_array();
    
    $updated = 0;
    
    foreach ($pendingTasks as $task) {
        // Update the status to reject
        $this->db->where('id_riwayatpekerjaan', $task['id_riwayatpekerjaan']);
        $this->db->update($this->table, ['status' => 'reject']);
        $updated++;
    }
    
    return $updated;
}

//batas dhasbord

public function get_riwayat_pekerjaan($start_date = null, $end_date = null, $jabatan = null, $pegawai = null) {
    $this->db->select('riwayat_pekerjaan.*, pekerjaan.nama_pekerjaan, pekerjaan.tipe_pekerjaan, pekerjaan.point, pegawai.nama_pegawai, jabatan.namajabatan');
    $this->db->from('riwayat_pekerjaan');
    $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'left');
    $this->db->join('pegawai', 'pegawai.uuid = riwayat_pekerjaan.pegawai_idpegawai', 'left');
    $this->db->join('jabatan', 'jabatan.idjabatan = pekerjaan.jabatan_idjabatan', 'left');
    
    // Apply date filter if provided
    if ($start_date && $end_date) {
        $this->db->where('DATE(riwayat_pekerjaan.created_at) >=', date('Y-m-d', strtotime($start_date)));
        $this->db->where('DATE(riwayat_pekerjaan.created_at) <=', date('Y-m-d', strtotime($end_date)));
    }
    
    // Apply jabatan filter if provided
    if ($jabatan) {
        $this->db->where('pekerjaan.jabatan_idjabatan', $jabatan);
    }
    
    // Apply pegawai filter if provided
    if ($pegawai) {
        $this->db->where('riwayat_pekerjaan.pegawai_idpegawai', $pegawai);
    }
    
    return $this->db->get()->result();
}

// Count work by status
public function count_by_status($status, $start_date = null, $end_date = null, $jabatan = null, $pegawai = null) {
    $this->db->select('COUNT(*) as total');
    $this->db->from('riwayat_pekerjaan');
    $this->db->where('status', $status);
    
    // Apply date filter if provided
    if ($start_date && $end_date) {
        $this->db->where('DATE(created_at) >=', date('Y-m-d', strtotime($start_date)));
        $this->db->where('DATE(created_at) <=', date('Y-m-d', strtotime($end_date)));
    }
    
    // Apply jabatan filter if provided
    if ($jabatan) {
        $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'left');
        $this->db->where('pekerjaan.jabatan_idjabatan', $jabatan);
    }
    
    // Apply pegawai filter if provided
    if ($pegawai) {
        $this->db->where('pegawai_idpegawai', $pegawai);
    }
    
    return $this->db->get()->row()->total;
}

// Get total count
public function count_total($start_date = null, $end_date = null, $jabatan = null, $pegawai = null) {
    $this->db->select('COUNT(*) as total');
    $this->db->from('riwayat_pekerjaan');
    
    // Apply date filter if provided
    if ($start_date && $end_date) {
        $this->db->where('DATE(created_at) >=', date('Y-m-d', strtotime($start_date)));
        $this->db->where('DATE(created_at) <=', date('Y-m-d', strtotime($end_date)));
    }
    
    // Apply jabatan filter if provided
    if ($jabatan) {
        $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'left');
        $this->db->where('pekerjaan.jabatan_idjabatan', $jabatan);
    }
    
    // Apply pegawai filter if provided
    if ($pegawai) {
        $this->db->where('pegawai_idpegawai', $pegawai);
    }
    
    return $this->db->get()->row()->total;
}

// Get today's tasks with pagination
// public function get_today_tasks($start_date = null, $end_date = null, $jabatan = null, $pegawai = null) {
//     $this->db->select('
//         riwayat_pekerjaan.pegawai_idpegawai, 
//         riwayat_pekerjaan.pekerjaan_idpekerjaan, 
//         riwayat_pekerjaan.status,
//         pekerjaan.jabatan_idjabatan, 
//         pekerjaan.tipe_pekerjaan, 
//         pekerjaan.point,
//         pekerjaan.nama_pekerjaan,
//         pegawai.nama_pegawai,
//         jabatan.namajabatan
//     ');
//     $this->db->from('riwayat_pekerjaan');
//     $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'left');
//     $this->db->join('pegawai', 'pegawai.uuid = riwayat_pekerjaan.pegawai_idpegawai', 'left');
//     $this->db->join('jabatan', 'jabatan.idjabatan = pekerjaan.jabatan_idjabatan', 'left');
    
//     // Filter by today's date if no date range is provided
//     if (!$start_date && !$end_date) {
//         $this->db->where('DATE(riwayat_pekerjaan.created_at)', date('Y-m-d'));
//     } else {
//         // Apply date filter if provided
//         $this->db->where('DATE(riwayat_pekerjaan.created_at) >=', date('Y-m-d', strtotime($start_date)));
//         $this->db->where('DATE(riwayat_pekerjaan.created_at) <=', date('Y-m-d', strtotime($end_date)));
//     }
    
//     // Apply jabatan filter if provided
//     if ($jabatan) {
//         $this->db->where('pekerjaan.jabatan_idjabatan', $jabatan);
//     }
    
//     // Apply pegawai filter if provided
//     if ($pegawai) {
//         $this->db->where('riwayat_pekerjaan.pegawai_idpegawai', $pegawai);
//     }

    
//     return $this->db->get()->result();
// }

    public function get_today_tasks($start_date = null, $end_date = null, $jabatan = null, $pegawai = null) {
        $this->db->select('
            rp.*,
            p.nama_pekerjaan,
            p.tipe_pekerjaan,
            p.point,
            pg.nama_pegawai,
            j.namajabatan
        ');
        $this->db->from('riwayat_pekerjaan rp');
        $this->db->join('pekerjaan p', 'p.id_pekerjaan = rp.pekerjaan_idpekerjaan', 'left');
        $this->db->join('pegawai pg', 'pg.uuid = rp.pegawai_idpegawai', 'left');
        $this->db->join('jabatan j', 'j.idjabatan = p.jabatan_idjabatan', 'left');
        
        // Filter tanggal
        if ($start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime(str_replace('-', '/', $start_date)));
            $end_date = date('Y-m-d', strtotime(str_replace('-', '/', $end_date)));
            $this->db->where('DATE(rp.created_at) >=', $start_date);
            $this->db->where('DATE(rp.created_at) <=', $end_date);
        } else {
            $this->db->where('DATE(rp.created_at)', date('Y-m-d'));
        }
        
        // Filter jabatan
        if ($jabatan) {
            $this->db->where('j.namajabatan', $jabatan);
        }
        
        // Filter pegawai
        if ($pegawai) {
            $this->db->where('rp.pegawai_idpegawai', $pegawai);
        }
        
        $this->db->order_by('rp.created_at', 'DESC');
        return $this->db->get()->result();
    }

// Get unique jabatan from pekerjaan table
public function get_jabatan_from_pekerjaan() {
    $this->db->select('DISTINCT(pekerjaan.jabatan_idjabatan) as id_jabatan, jabatan.namajabatan');
    $this->db->from('pekerjaan');
    $this->db->join('jabatan', 'jabatan.idjabatan = pekerjaan.jabatan_idjabatan', 'left');
    $this->db->order_by('jabatan.namajabatan', 'ASC');
    return $this->db->get()->result();
}

// Get unique pegawai from riwayat_pekerjaan table
public function get_pegawai_from_riwayat() {
    $this->db->select('DISTINCT(riwayat_pekerjaan.pegawai_idpegawai) as uuid, pegawai.nama_pegawai');
    $this->db->from('riwayat_pekerjaan');
    $this->db->join('pegawai', 'pegawai.uuid = riwayat_pekerjaan.pegawai_idpegawai', 'left');
    $this->db->order_by('pegawai.nama_pegawai', 'ASC');
    return $this->db->get()->result();
}
}

