using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace ThuongMaiDienTu_DoAn.Models
{
    public class DanhGiaViewModel
    {

        public int MaHD { get; set; }        
        public int MaSP { get; set; }       

        public string TenSP { get; set; }   
        public string Hinh { get; set; }      

        public bool DaDanhGia { get; set; }
    }
}