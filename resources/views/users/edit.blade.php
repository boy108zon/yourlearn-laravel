@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
   
    <div class="container-fluid">
        <div class="card bg-white">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile</h5>
            </div>

            <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                          <label for="password" class="form-label">Password (Leave empty to keep current)</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="extension" class="form-label">Extension <span class="text-danger">*</span></label>
                            <input type="text" id="extension" name="extension" class="form-control @error('extension') is-invalid @enderror" value="{{ old('extension', $user->extension) }}" required>
                            @error('extension')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile No <span class="text-danger">*</span></label>
                            <input type="text" id="mobile" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $user->mobile) }}" required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Alternate No.</label>
                            <input type="text" id="alternate_no" name="alternate_no" class="form-control @error('alternate_no') is-invalid @enderror" value="{{ old('alternate_no', $user->alternate_no) }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <select name="country_id" id="country" class="form-control @error('country_id') is-invalid @enderror">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (isset($user) && $user->country_id == $country->id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                            <select name="state_id" id="state" class="form-control @error('state_id') is-invalid @enderror">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ (isset($user) && $user->state_id == $state->id) ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="city_id" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" id="city_id" name="city_id" class="form-control @error('city_id') is-invalid @enderror" value="{{ old('city_id', $user->city_id) }}" required>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                            <input type="text" id="pincode" name="pincode" class="form-control @error('pincode') is-invalid @enderror" value="{{ old('pincode', $user->pincode) }}" required>
                            @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Profile Photo</label>
                                <input type="file" name="file" id="file" class="form-control" accept=".png, .jpg, .jpeg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roles" class="form-label">Roles <span class="text-danger">*</span></label>
                                <select id="roles" name="roles[]" class="form-control @error('roles') is-invalid @enderror" multiple required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            @if(in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : []))) selected @endif>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                
                @if($user->profile_picture)
                   <img src="{{ asset($user->profile_picture) }}" alt="Profile Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                @endif
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary active">Save Changes</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
           </div>
        </div>
    </div>
@endsection
