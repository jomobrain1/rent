@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                            <tr>
                                <th></th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($types as $item)
                                <tr>
                                    <td data-label="@lang('Type')">
                                        <div class="user">
                                            <div class="thumb"><img src="{{ getImage(imagePath()['types']['path'].'/'. $item->image,imagePath()['types']['size'])}}" alt="@lang('image')"></div>
                                            <span class="name">{{__($item->name)}}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Name')"><strong>{{ __($item->name) }}</strong></td>
                                    <td data-label="@lang('Status')">
                                        @if($item->status == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Deactive')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn ml-1 editBtn" data-original-title="@lang('Edit')" data-toggle="tooltip" data-url="{{ route('admin.type.update', $item->id)}}" data-name="{{ $item->name }}" data-image="{{ getImage(imagePath()['types']['path'].'/'.$item->image,imagePath()['types']['size']) }}" >
                                            <i class="la la-edit"></i>
                                        </a>

                                        <a href="javascript:void(0)" class="icon-btn {{ $item->status ? 'btn--danger' : 'btn--success' }} ml-1 statusBtn" data-original-title="@lang('Status')" data-toggle="tooltip" data-url="{{ route('admin.type.status', $item->id) }}">
                                            <i class="la la-eye{{ $item->status ? '-slash' : null }}"></i>
                                        </a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($types) }}
                </div>
            </div><!-- card end -->
        </div>
    </div>



    {{-- NEW MODAL --}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> @lang('Add New')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form class="form-horizontal" method="post" action="{{ route('admin.type.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row form-group">
                            <label for="name" class="font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control has-error bold" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="form-row form--group ">
                            <label for="image" class="bg--primary">@lang('Image')</label>
                            <input type="file" name="image" class="form-control form--control" accept="image/*">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary" id="btn-save" value="add">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-fw fa-share-square"></i>@lang('Edit')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row form-group">
                            <label for="name" class="font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control has-error bold" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="thumb">
                            <div class="avatar-preview">
                                <img id="avatar" alt="type">
                            </div>
                            <div class="avatar-edit">
                                <input type="file" name="image" class="profilePicUpload" id="image" accept=".png, .jpg, .jpeg"/>
                                <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary" id="btn-save" value="add">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Status MODAL --}}
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('Update Status')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" action="">
                    @csrf

                    <div class="modal-body">
                        <p class="text-muted">@lang('Are you sure to remove this type?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--danger deleteButton">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a class="btn btn-sm btn--primary box--shadow1 text-white text--small" data-toggle="modal" data-target="#myModal"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";

            // Edit
            $('.editBtn').on('click', function () {
                var modal = $('#editModal');
                var url = $(this).data('url');
                var name = $(this).data('name');
                var image = $(this).data('image');

                modal.find('form').attr('action', url);
                modal.find('input[name=name]').val(name);
                modal.find('#avatar').attr('src', image);
                modal.modal('show');
            });

            //Status
            $('.statusBtn').on('click', function () {
                var modal = $('#statusModal');
                var url = $(this).data('url');

                modal.find('form').attr('action', url);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
