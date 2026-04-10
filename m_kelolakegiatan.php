<?php
class m_kelolakegiatan extends CI_Model{
    private $table="t_data";
    
    function cek($id,$data){
        $this->db->where("id",$id);
        $this->db->where("data",$data);
        return $this->db->get("t_data");
    }

    function get_statistik_dashboard($th){
        $sql = "SELECT 
                    SUM(CASE WHEN is_periksa = 1 THEN 1 ELSE 0 END) as diisi,
                    SUM(CASE WHEN is_periksa = 0 THEN 1 ELSE 0 END) as belum_diisi,
                    SUM(CASE WHEN is_confirm = 1 THEN 1 ELSE 0 END) as sudah_acc,
                    SUM(CASE WHEN is_confirm != 1 THEN 1 ELSE 0 END) as belum_acc
                FROM t_list_tabel 
                WHERE tahun = '$th'"; 

        return $this->db->query($sql)->row();
    }

    
    function get_statistik_per_opd($th){
        $sql = "SELECT 
                    m.id_unitkerja as nama_opd, 
                    
                    -- HIJAU
                    SUM(CASE WHEN t.is_confirm = 1 THEN 1 ELSE 0 END) as total_acc,
                    -- KUNING
                    SUM(CASE WHEN t.is_periksa = 1 AND t.is_confirm != 1 THEN 1 ELSE 0 END) as total_menunggu,
                    -- MERAH
                    SUM(CASE WHEN t.is_periksa = 0 THEN 1 ELSE 0 END) as total_belum
                    
                FROM t_list_tabel t  
                JOIN m_unitkerja m ON t.id_unitkerja = m.id_unitkerja 
                WHERE t.tahun = '$th' 
                GROUP BY m.id_unitkerja
                ORDER BY m.id_unitkerja ASC"; 
        
        return $this->db->query($sql)->result();
    }

    function get_statistik_per_tim($th){
        $sql = "SELECT 
                    m.user_wali as nama_tim,
                    -- 1. HIJAU
                    SUM(CASE WHEN t.is_confirm = 1 THEN 1 ELSE 0 END) as total_acc,
                    -- 2. KUNING
                    SUM(CASE WHEN t.is_periksa = 1 AND t.is_confirm != 1 THEN 1 ELSE 0 END) as total_menunggu,
                    -- 3. MERAH
                    SUM(CASE WHEN t.is_periksa = 0 AND t.id IS NOT NULL THEN 1 ELSE 0 END) as total_belum
                    
                FROM m_unitkerja m
                LEFT JOIN t_list_tabel t ON m.id_unitkerja = t.id_unitkerja AND t.tahun = '$th'
                WHERE m.user_wali IS NOT NULL AND m.user_wali != '' AND m.user_wali != '-'
                GROUP BY m.user_wali
                ORDER BY m.user_wali ASC";
        
        return $this->db->query($sql)->result();
    }
}
