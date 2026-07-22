using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace ThuongMaiDienTu_DoAn.Models
{
    public class HoaDonDaBanViewModel
    {
        public int MaHD { get; set; }
        public DateTime? NgayDat { get; set; }
        public DateTime? NgayTT { get; set; }
        public string NguoiMua { get; set; }
        public decimal TongTien { get; set; }
        public string TrangThai { get; set; }
    }
}