function viewStockRequestDT($in, $opt = true)
	{
		$start = $in->start;
		$sqlproduct = "";
		if (!empty($in->id_so)) {
			$sqlproduct = "SELECT distinct id_sub_barang FROM t_detail_po WHERE deleted_at IS NULL AND id_po = '$in->id_so' ";
		}
		if (!empty($in->id_po)) {
			$sqlproduct = "SELECT distinct id_sub_barang FROM t_detail_po WHERE deleted_at IS NULL AND id_po = '$in->id_po' ";
		}
		$sqlrequest = "SELECT x.id_sub_barang, x.kode_barang, x.nama_barang, x.id_satuan, sum( x.qty_pending ) qty_request FROM ( SELECT ta.id_request, tb.kode_request, tb.tgl_request, tb.id_jenis_mutasi, tf.nama_jenis_mutasi, ta.id_sub_barang, td.kode_barang, td.nama_barang, ta.id_satuan, te.KODE_SATUAN kode_satuan, ta.qty_request, IFNULL( tc.qty_issue, 0 ) qty_issue, ta.qty_request - IFNULL( tc.qty_issue, 0 ) qty_pending FROM t_request_detail ta INNER JOIN t_request tb ON tb.id_request = ta.id_request LEFT JOIN ( SELECT tb.id_request, ta.id_sub_barang, ta.id_satuan, SUM( ta.qty ) qty_issue FROM t_wh_buffer ta INNER JOIN t_wh tb ON tb.id_wh = ta.id_wh WHERE ta.deleted_at IS NULL AND tb.deleted_at IS NULL ".(!empty($in->id_so) || !empty($in->id_po) ? " AND ta.id_sub_barang IN ($sqlproduct) " : "")." GROUP BY tb.id_request, ta.id_sub_barang, ta.id_satuan ) tc ON tc.id_request = ta.id_request AND tc.id_sub_barang = ta.id_sub_barang AND tc.id_satuan = ta.id_satuan LEFT JOIN m_sub_barang td ON td.id_sub_barang = ta.id_sub_barang LEFT JOIN ".getdbtpb($this).".referensi_satuan te ON te.ID = ta.id_satuan LEFT JOIN m_jenis_mutasi tf ON tf.id_jenis_mutasi = tb.id_jenis_mutasi WHERE ta.deleted_at IS NULL AND tb.deleted_at IS NULL AND tb.closed_at IS NULL AND ta.qty_request - IFNULL( tc.qty_issue, 0 ) > 0 ".(!empty($in->id_so) || !empty($in->id_po) ? " AND ta.id_sub_barang IN ($sqlproduct) " : "").") x GROUP BY x.id_sub_barang, x.kode_barang, x.nama_barang, x.id_satuan";

		$sqlmain = "SELECT ta.id_sub_barang, tb.kode_barang, tb.external_code, tb.nama_barang, tb.size, ta.id_satuan_terkecil as id_satuan, te.KODE_SATUAN AS kode_satuan, tb.nama_class, SUM( ta.qty ) AS qty_stock, IFNULL(tf.qty_request, 0) as qty_pending FROM ( SELECT * from t_wh_detail where deleted_at is null ) ta LEFT JOIN v_sub_barang tb ON tb.id_sub_barang = ta.id_sub_barang LEFT JOIN t_wh tc ON tc.id_wh = ta.id_wh LEFT JOIN m_jenis_mutasi td ON td.id_jenis_mutasi = tc.id_jenis_mutasi LEFT JOIN ".getdbtpb($this).".referensi_satuan te ON te.ID = ta.id_satuan_terkecil LEFT JOIN ($sqlrequest) tf ON tf.id_sub_barang = ta.id_sub_barang AND tf.id_satuan = ta.id_satuan_terkecil LEFT JOIN m_koordinat tg on tg.id_koordinat = ta.id_koordinat LEFT JOIN m_gudang th on th.id_gudang = tg.id_gudang LEFT JOIN m_jenis_gudang ti on ti.id_jenis_gudang = th.id_jenis_gudang WHERE tc.approval_1 = '1' AND tc.approval_2 = '1' AND tc.deleted_at IS NULL AND ti.id_status = '1' ";
		if (empty($in->id_jenis_mutasi)) {
			$sqlmain .= " AND 1 = 2 ";
		}
		$sqlmain .= " GROUP BY ta.id_sub_barang, tb.kode_barang, tb.nama_barang, ta.id_satuan_terkecil, te.KODE_SATUAN, tb.nama_class HAVING SUM( ta.qty ) > 0 ";

		if (!empty($in->id_so)) {
			$sqlstock = "SELECT ta.id_sub_barang, ta.id_satuan_terkecil AS id_satuan, SUM( ta.qty ) AS qty_stock FROM ( SELECT * from t_wh_detail where deleted_at is null ) ta LEFT JOIN t_wh tc ON tc.id_wh = ta.id_wh LEFT JOIN m_jenis_mutasi td ON td.id_jenis_mutasi = tc.id_jenis_mutasi LEFT JOIN m_koordinat tg ON tg.id_koordinat = ta.id_koordinat LEFT JOIN m_gudang th ON th.id_gudang = tg.id_gudang LEFT JOIN m_jenis_gudang ti ON ti.id_jenis_gudang = th.id_jenis_gudang WHERE ta.deleted_at IS NULL AND tc.approval_1 = '1' AND tc.approval_2 = '1' AND tc.deleted_at IS NULL AND ti.id_status = '1' AND ta.id_sub_barang IN ($sqlproduct) GROUP BY ta.id_sub_barang, ta.id_satuan_terkecil";
			$sqlmain = "SELECT xa.id_sub_barang, tb.kode_barang, tb.external_code, tb.nama_barang, tb.size, xa.id_satuan, te.KODE_SATUAN AS kode_satuan, tj.nama_class, IFNULL(ta.qty_stock, 0) AS qty_stock, IFNULL(tf.qty_request, 0) as qty_pending FROM t_detail_po xa LEFT JOIN ($sqlstock) ta ON ta.id_sub_barang = xa.id_sub_barang AND ta.id_satuan = xa.id_satuan LEFT JOIN m_sub_barang tb ON tb.id_sub_barang = xa.id_sub_barang LEFT JOIN ".getdbtpb($this).".referensi_satuan te ON te.ID = xa.id_satuan LEFT JOIN ($sqlrequest) tf ON tf.id_sub_barang = ta.id_sub_barang AND tf.id_satuan = xa.id_satuan LEFT JOIN m_class tj ON tj.id_class = tb.id_class WHERE 1 = 1 AND xa.deleted_at IS NULL AND xa.id_po = '$in->id_so'";
		}
		if (!empty($in->id_po)) {
			$sqlstock = "SELECT ta.id_sub_barang, ta.id_satuan_terkecil AS id_satuan, SUM( ta.qty ) AS qty_stock FROM ( SELECT * from t_wh_detail where deleted_at is null ) ta LEFT JOIN t_wh tc ON tc.id_wh = ta.id_wh LEFT JOIN m_jenis_mutasi td ON td.id_jenis_mutasi = tc.id_jenis_mutasi LEFT JOIN m_koordinat tg ON tg.id_koordinat = ta.id_koordinat LEFT JOIN m_gudang th ON th.id_gudang = tg.id_gudang LEFT JOIN m_jenis_gudang ti ON ti.id_jenis_gudang = th.id_jenis_gudang WHERE ta.deleted_at IS NULL AND tc.approval_1 = '1' AND tc.approval_2 = '1' AND tc.deleted_at IS NULL AND ti.id_status = '1' AND ta.id_sub_barang IN ($sqlproduct) GROUP BY ta.id_sub_barang, ta.id_satuan_terkecil";
			$sqlmain = "SELECT xa.id_sub_barang, tb.kode_barang, tb.external_code, tb.nama_barang, tb.size, xa.id_satuan, te.KODE_SATUAN AS kode_satuan, tj.nama_class, IFNULL(ta.qty_stock, 0) AS qty_stock, IFNULL(tf.qty_request, 0) as qty_pending FROM t_detail_po xa LEFT JOIN ($sqlstock) ta ON ta.id_sub_barang = xa.id_sub_barang AND ta.id_satuan = xa.id_satuan LEFT JOIN m_sub_barang tb ON tb.id_sub_barang = xa.id_sub_barang LEFT JOIN ".getdbtpb($this).".referensi_satuan te ON te.ID = xa.id_satuan LEFT JOIN ($sqlrequest) tf ON tf.id_sub_barang = ta.id_sub_barang AND tf.id_satuan = xa.id_satuan LEFT JOIN m_class tj ON tj.id_class = tb.id_class WHERE 1 = 1 AND xa.deleted_at IS NULL AND xa.id_po = '$in->id_po'";
		}
		if (!empty($in->id_bom) && isset($in->status_stock)){
			$sqlmain = "SELECT ta.id_sub_barang, tb.kode_barang, tb.external_code, tb.nama_barang, tb.size, ta.id_satuan_terkecil as id_satuan, te.KODE_SATUAN AS kode_satuan, tb.nama_class, SUM( ta.qty ) AS qty_stock, IFNULL(tf.qty_request, 0) as qty_pending FROM ( SELECT * from t_wh_detail where deleted_at is null ) ta INNER JOIN (SELECT * FROM t_bom_detail where id_bom = '$in->id_bom' and deleted_at is null) bom on ta.id_sub_barang = bom.id_sub_barang LEFT JOIN v_sub_barang tb ON tb.id_sub_barang = ta.id_sub_barang LEFT JOIN t_wh tc ON tc.id_wh = ta.id_wh LEFT JOIN m_jenis_mutasi td ON td.id_jenis_mutasi = tc.id_jenis_mutasi LEFT JOIN ".getdbtpb($this).".referensi_satuan te ON te.ID = ta.id_satuan_terkecil LEFT JOIN ($sqlrequest) tf ON tf.id_sub_barang = ta.id_sub_barang AND tf.id_satuan = ta.id_satuan_terkecil LEFT JOIN m_koordinat tg on tg.id_koordinat = ta.id_koordinat LEFT JOIN m_gudang th on th.id_gudang = tg.id_gudang LEFT JOIN m_jenis_gudang ti on ti.id_jenis_gudang = th.id_jenis_gudang WHERE ta.deleted_at IS NULL AND tc.deleted_at IS NULL AND ta.deleted_at IS NULL and tc.approval_1 = '1' AND tc.approval_2 = '1' AND ti.id_status = '1' GROUP BY ta.id_sub_barang, tb.kode_barang, tb.nama_barang, ta.id_satuan_terkecil, te.KODE_SATUAN, tb.nama_class HAVING SUM( ta.qty ) > 0";
		}
		// printJSON($sqlmain);
		$sql = "select * from ($sqlmain) pa";
		$res = $this->db->query($sql);
		$recordsTotal = $res->num_rows();

		$sql .= dtSearch($this, $in);
		$res = $this->db->query($sql);
		$recordsFiltered = $res->num_rows();

		$sql .= dtSort($in);
		$sql .= dtLimit($in);
		$res = $this->db->query($sql);
		$num = $res->num_rows();

		$data = array();
		if($num>0){
			$i=$start+1;
			foreach ($res->result() as $r){
				$r->no = $i;
				$r->blank = '';

				if($opt){
					$r->option = "<button class='btn btn-xs btn-success btn-detail'><i class='fa fal fa-plus-circle'></i> Show</button>";
				}
				$data[] = $r;
				$i++;
			}
		}
		$k = new stdClass();
		$k->draw = $in->draw;
		$k->recordsTotal = $recordsTotal;
		$k->recordsFiltered = $recordsFiltered;
		$k->data = $data;

		return $k;
	}
