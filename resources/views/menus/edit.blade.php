@extends('layouts.master')

@section('title', 'Edit Menu')

@section('content')
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Menu: <b>{{ $menu->name }}</b></h5>
            </div>

            <div class="card-body">
                <form action="{{ route('menus.update', $menu->id) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $menu->name) }}" required autocomplete="off" aria-describedby="nameHelp">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Menu Title</label>
                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $menu->title) }}" autocomplete="off" aria-describedby="titleHelp">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url" class="form-label">Menu URL <span class="text-danger">*</span></label>
                                <input type="text" id="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $menu->url) }}" required autocomplete="off" aria-describedby="urlHelp">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug" class="form-label">Menu Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $menu->slug) }}" autocomplete="off" aria-describedby="slugHelp" disabled>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sequence" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                <input type="number" id="sequence" name="sequence" class="form-control @error('sequence') is-invalid @enderror" value="{{ old('sequence', $menu->sequence) }}" required autocomplete="off" aria-describedby="sequenceHelp">
                                @error('sequence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ $menu->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $menu->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Menu Icon</label>
                                <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $menu->icon) }}" autocomplete="off">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Assign Parent Menu</label>
                                            <div class="form-group">
                                                <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror" aria-describedby="parentHelp">
                                                    <option value="0">None (Top Level)</option>

                                                    @foreach($menus as $parentMenu)
                                                        
                                                        @if($parentMenu->parent_id == 0)
                                                            <option value="{{ $parentMenu->id }}" {{ $menu->parent_id == $parentMenu->id ? 'selected' : '' }}>
                                                                {{ $parentMenu->name }}
                                                            </option>  
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('parent_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-bd-primary">Save</button>
                                <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
        </div>
    </div>
@endsection
