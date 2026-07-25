using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace ThuongMaiDienTu_DoAn.Models
{
    public class KhieuNaiViewModel
    {
        public int MaKN { get; set; }
        public int MaKH { get; set; }
        public string TenNguoiGui { get; set; }
        public int MaSP { get; set; }
        public string TenSP { get; set; }
        public string MoTa { get; set; }
        public DateTime NgayGui { get; set; }
        public string TrangThai { get; set; }
    }
}