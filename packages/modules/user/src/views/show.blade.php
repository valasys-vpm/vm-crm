@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">User Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('user') }}">User Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">User Details</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ task-detail ] start -->
                                <div class="col-xl-4 col-lg-12 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col-auto">
                                                    <img class="img-fluid rounded-circle" style="width:80px;" src="{{asset('public/template')}}/assets/images/user/avatar-2.jpg" alt="dashboard-user">
                                                </div>
                                                <div class="col">
                                                    <h5>{{ $resultUser->userDetail->first_name.' '.$resultUser->userDetail->last_name }}</h5>
                                                    <span>{{ $resultUser->userDetail->designation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>User Details</h5>
                                        </div>
                                        <div class="card-block task-details">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>Email:</td>
                                                    <td class="text-right"><span class="float-right">{{ $resultUser->email }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Employee Code:</td>
                                                    <td class="text-right"><span class="float-right">{{ $resultUser->userDetail->emp_code }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Department:</td>
                                                    <td class="text-right">{{ $resultUser->userDetail->department }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Designation:</td>
                                                    <td class="text-right">{{ $resultUser->userDetail->designation }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Status:</td>
                                                    <td class="text-right">
                                                        @switch($resultUser->status)
                                                            @case(\Modules\User\enum\UserStatus::ACTIVE)
                                                            {{ \Modules\User\enum\UserStatus::USER_STATUS[$resultUser->status] }}
                                                            @break
                                                            @case(\Modules\User\enum\UserStatus::INACTIVE)
                                                            {{ \Modules\User\enum\UserStatus::USER_STATUS[$resultUser->status] }}
                                                            @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-lg-12">
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col-auto">
                                                    <img class="img-fluid rounded-circle" style="width:70px;" src="{{asset('public/template')}}/assets/images/user/avatar-2.jpg" alt="dashboard-user">
                                                </div>
                                                <div class="col">
                                                    <h5>User History</h5>
                                                </div>
                                            </div>
                                            <ul class="task-list">
                                                @forelse($resultHistories as $history)
                                                    <li>
                                                        <i class="task-icon bg-c-green"></i>
                                                        <h6>{{ $history->action }}<span class="float-right text-muted">{{ date('d M, Y \a\t h:i A', strtotime($history->created_at)) }}</span></h6>
                                                        <p class="text-muted">{!! $history->message !!}</p>
                                                    </li>
                                                @empty
                                                    <li>
                                                        <i class="task-icon bg-c-red"></i>
                                                        <h6>No Data Found</h6>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-3"><i class="fas fa-ticket-alt m-r-5"></i> Ticket List Doesn't Support Commas</h5>
                                            <button class="btn btn-primary float-right"><i class="far fa-bell m-r-5"></i>Check in</button>
                                        </div>
                                        <div class="card-block">
                                            <div class="m-b-20">
                                                <h6>Overview</h6>
                                                <hr>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book.</p>
                                            </div>
                                            <div class="m-b-20">
                                                <h6>What we need</h6>
                                                <hr>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                            </div>
                                            <div class="m-b-20 col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-6">
                                                        <div class="text-primary f-14 m-b-10">
                                                            1. The standard Lorem Ipsum passage
                                                        </div>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                                                    </div>
                                                    <div class="col-md-12 col-lg-6">
                                                        <div class="text-primary f-14 m-b-10">
                                                            2. The standard Lorem Ipsum passage
                                                        </div>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="m-b-20">
                                                <h6>Requirements</h6>
                                                <hr>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                                <div class="table-responsive m-t-20">
                                                    <table class="table m-b-0 f-14 b-solid requid-table">
                                                        <thead>
                                                        <tr class="text-uppercase">
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Task</th>
                                                            <th class="text-center">Due Date</th>
                                                            <th class="text-center">Description</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-center text-muted">
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Design mockup</td>
                                                            <td> <i class="far fa-calendar-alt"></i>&nbsp; 22 December, 16</td>
                                                            <td>The standard Lorem Ipsum</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Software Engineer</td>
                                                            <td> <i class="far fa-calendar-alt"></i>&nbsp; 01 December, 16</td>
                                                            <td>The standard Lorem passage</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Photoshop And Illustrator</td>
                                                            <td> <i class="far fa-calendar-alt"></i>&nbsp; 15 December, 16</td>
                                                            <td>The standard Lorem Ipsum</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Allocated Resource</td>
                                                            <td> <i class="far fa-calendar-alt"></i>&nbsp; 28 December, 16</td>
                                                            <td>The standard Lorem passage</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Financial Controlle</td>
                                                            <td> <i class="far fa-calendar-alt"></i>&nbsp; 20 December, 16</td>
                                                            <td>The standard Lorem Ipsum</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="float-left mt-4">
                                                <span class=" txt-primary"> <i class="fas fa-chart-line"></i>
                                                    Status:</span>
                                                <div class="dropdown-secondary dropdown d-inline-block">
                                                    <button class="btn btn btn-primary dropdown-toggle " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Open</button>
                                                    <div class="dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                        <a class="dropdown-item active" href="#!">Open</a>
                                                        <a class="dropdown-item" href="#!">On hold</a>
                                                        <a class="dropdown-item" href="#!">Resolved</a>
                                                        <a class="dropdown-item" href="#!">Closed</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#!">Dublicate</a>
                                                        <a class="dropdown-item" href="#!">Invalid</a>
                                                        <a class="dropdown-item" href="#!">Wontfix</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="float-right d-flex mt-4">
                                                <span>
                                                    <a href="#!" class="text-muted m-r-10 f-16"><i class="fas fa-edit"></i> </a>
                                                </span>
                                                <span class="m-r-10">
                                                    <a href="#!" class="text-muted f-16"><i class="fas fa-trash"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-header-text"><i class="fas fa-plus m-r-5"></i> Comments</h5>
                                            <button type="button" class="btn btn-icon btn-primary float-right m-0">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="card-block task-comment">
                                            <ul class="media-list p-0">
                                                <li class="media">
                                                    <div class="media-left mr-3">
                                                        <a href="#!">
                                                            <img class="media-object img-radius comment-img" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading txt-primary">Lorem Ipsum <span class="f-12 text-muted ml-1">Just now</span></h6>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                                                        <div class="m-t-10 m-b-25">
                                                            <span><a href="#!" class="m-r-10 text-secondary">Reply</a></span><span><a href="#!" class="m-r-10 text-secondary">Edit</a> </span>
                                                        </div>
                                                        <hr>
                                                        <div class="media mt-2">
                                                            <a class="media-left mr-3" href="#!">
                                                                <img class="media-object img-radius comment-img" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                                            </a>
                                                            <div class="media-body">
                                                                <h6 class="media-heading txt-primary">Lorem Ipsum <span class="f-12 text-muted ml-1">Just now</span></h6>
                                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                                                                <div class="m-t-10 m-b-25">
                                                                    <span><a href="#!" class="m-r-10 text-secondary">Reply</a></span><span><a href="#!" class="m-r-10 text-secondary">Edit</a> </span>
                                                                </div>
                                                                <hr>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="media mt-2">
                                                    <div class="media-left mr-3">
                                                        <a href="#!">
                                                            <img class="media-object img-radius comment-img" src="assets/images/user/avatar-3.jpg" alt="Generic placeholder image">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading txt-primary">Lorem ipsum<span class="f-12 text-muted ml-1">Just now</span></h6>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                                                        <div class="m-t-10 m-b-25">
                                                            <span><a href="#!" class="m-r-10 text-secondary">Reply</a></span><span><a href="#!" class="m-r-10 text-secondary">Edit</a> </span>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Add Task...">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary btn-icon" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ task-detail ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
