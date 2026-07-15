using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace ThuongMaiDienTu_DoAn.Models
{
    public class ChiTietHoaDonViewModel
    {
        public int MaHD { get; set; }
        public int MaSP { get; set; }
        public string TenSP { get; set; }
        public int SoLuong { get; set; }
        public decimal ThanhTien { get; set; }
        public string TrangThaiCT { get; set; }
        public bool DaDanhGia { get; set; }
        public bool DaKhieuNai { get; set; }
    }

}