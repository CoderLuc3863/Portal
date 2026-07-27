@include('includes.header')
     @include('includes.sidebar-menu')
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main bg-body"  id="main-content">
        <!--begin::App Content Header-->    
          @include('pages.dashboard-content')
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
     @include('includes.footer')