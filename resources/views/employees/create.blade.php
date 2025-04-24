@extends('layouts.dashboard')

@section('title', isset($employee) ? 'تعديل موظف' : 'إضافة موظف جديد')

@section('header', isset($employee) ? 'تعديل موظف' : 'إضافة موظف جديد')

@section('content')
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200">
        <form action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($employee))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Employee Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم الموظف</label>
                    <input type="text" name="name" id="name" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ old('name', $employee->name ?? '') }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employee Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ old('email', $employee->email ?? '') }}" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employee Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">المنصب الوظيفي</label>
                    <input type="text" name="position" id="position" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ old('position', $employee->position ?? '') }}" required>
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employee Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">القسم</label>
                    <select name="department_id" id="department_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            required>
                        <option value="">اختر القسم</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->id }}" {{ (old('department_id', $employee->department_id ?? '') == $department->id) ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employee Photo -->
                <div>
                    <label for="profile_photo" class="block text-sm font-medium text-gray-700">الصورة الشخصية</label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           {{ isset($employee) ? '' : 'required' }}>
                    @error('profile_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if(isset($employee) && $employee->profile_photo_url)
                        <div class="mt-2">
                            <img src="{{ $employee->profile_photo_url }}" alt="Current profile photo" class="h-32 w-32 rounded-full">
                        </div>
                    @endif
                </div>

                <!-- Password Fields (Only for new employees) -->
                @if(!isset($employee))
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                    <input type="password" name="password" id="password" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           required>
                </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        {{ isset($employee) ? 'تحديث الموظف' : 'إضافة الموظف' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 