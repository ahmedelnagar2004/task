@extends('layouts.dashboard')

@section('title', isset($department) ? 'تعديل قسم' : 'إضافة قسم جديد')

@section('header', isset($department) ? 'تعديل قسم' : 'إضافة قسم جديد')

@section('content')
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200">
        <form action="{{ isset($department) ? route('departments.update', $department) : route('departments.store') }}" method="POST">
            @csrf
            @if(isset($department))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Department Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم القسم</label>
                    <input type="text" name="name" id="name" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ old('name', $department->name ?? '') }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">وصف القسم</label>
                    <textarea name="description" id="description" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                              required>{{ old('description', $department->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department Manager -->
                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700">مدير القسم</label>
                    <select name="manager_id" id="manager_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            required>
                        <option value="">اختر مدير القسم</option>
                        @foreach($employees ?? [] as $employee)
                            <option value="{{ $employee->id }}" {{ (old('manager_id', $department->manager_id ?? '') == $employee->id) ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        {{ isset($department) ? 'تحديث القسم' : 'إضافة القسم' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 