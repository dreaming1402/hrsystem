<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="./app/hrsystem/public/template/img/avatar.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Long Thai</p>
                <a href="#">
                    <i class="fa fa-circle text-success"></i>
                    Online
                </a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
<!---->
            <li class="header">QUẢN LÝ NHÂN SỰ</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Nhân viên</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Employee&a=management">
                            <i class="fa fa-list"></i>
                            Danh sách
                        </a>
                    </li>

                    <li>
                        <a href="?c=Employee&a=edit">
                            <i class="fa fa-pencil-square-o"></i>
                            Thêm mới
                        </a>
                    </li>

                    <li>
                        <a href="?c=Employee&a=trash">
                            <i class="fa fa-trash"></i>
                            Thùng rác
                        </a>
                    </li>
                    
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-handshake-o"></i>
                    <span>Hợp đồng lao động</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Contract&a=management">
                            <i class="fa fa-list"></i>
                            Danh sách
                        </a>
                    </li>

                    <li>
                        <a href="?c=Contract&a=new">
                            <i class="fa fa-pencil-square-o"></i>
                            Thêm mới
                        </a>
                    </li>

                    <li>
                        <a href="?c=Contract&a=trash">
                            <i class="fa fa-trash"></i>
                            Thùng rác
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-briefcase"></i>
                    <span>Bảo hiểm xã hội</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Social&a=management">
                            <i class="fa fa-list"></i>
                            Danh sách
                        </a>
                    </li>

                    <li>
                        <a href="?c=Social&a=new">
                            <i class="fa fa-pencil-square-o"></i>
                            Thêm mới
                        </a>
                    </li>

                    <li>
                        <a href="?c=Social&a=trash">
                            <i class="fa fa-trash"></i>
                            Thùng rác
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-heartbeat"></i>
                    <span>Bảo hiểm y tế</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Health&a=management">
                            <i class="fa fa-list"></i>
                            Danh sách
                        </a>
                    </li>

                    <li>
                        <a href="?c=Health&a=new">
                            <i class="fa fa-pencil-square-o"></i>
                            Thêm mới
                        </a>
                    </li>

                    <li>
                        <a href="?c=Health&a=trash">
                            <i class="fa fa-trash"></i>
                            Thùng rác
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-child"></i>
                    <span>Chế độ thai sản</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Maternity&a=management">
                            <i class="fa fa-list"></i>
                            Danh sách
                        </a>
                    </li>

                    <li>
                        <a href="?c=Maternity&a=new">
                            <i class="fa fa-pencil-square-o"></i>
                            Thêm mới
                        </a>
                    </li>

                    <li>
                        <a href="?c=Maternity&a=trash">
                            <i class="fa fa-trash"></i>
                            Thùng rác
                        </a>
                    </li>
                </ul>
            </li>

            <li class="header">TÁC VỤ</li>
            <li class="treeview active">
                <a href="#">
                    <i class="fa fa-credit-card"></i>
                    <span>Thẻ đeo nhân viên</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Employee&a=PrintCard">
                            <i class="fa fa-print"></i>
                            In thẻ nhân viên
                        </a>
                    </li>

                    <li>
                        <a href="?c=PrintCard&a=history">
                            <i class="fa fa-history"></i>
                            Lịch sử in thẻ
                        </a>
                    </li>

                    <li>
                        <a href="?c=PrintCard&a=trash">
                            <i class="fa fa-trash"></i>
                            Thùng rác
                        </a>
                    </li>
                    
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-support"></i>
                    <span>Tải lên</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?c=Employee&a=importData">
                            <i class="fa fa-upload"></i>
                            Tải lên danh sách NV
                        </a>
                    </li>

                    <li>
                        <a href="?c=Employee&a=uploadImage">
                            <i class="fa fa-upload"></i>
                            Tải lên ảnh thẻ
                        </a>
                    </li>

                    <li>
                        <a href="?c=PrintCard&a=importData">
                            <i class="fa fa-upload"></i>
                            Tải lên danh sách đã in
                        </a>
                    </li>
                </ul>
            </li>
<!---->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>