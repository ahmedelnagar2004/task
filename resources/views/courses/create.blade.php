@extends('layouts.dashboard')

@section('title', isset($course) ? 'تعديل دورة تدريبية' : 'إضافة دورة تدريبية جديدة')

@section('header', isset($course) ? 'تعديل دورة تدريبية' : 'إضافة دورة تدريبية جديدة')

@section('content')
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200">
        <form action="{{ isset($course) ? route('courses.update', $course) : route('courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($course))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Course Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">عنوان الدورة</label>
                    <input type="text" name="title" id="title" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ old('title', $course->title ?? '') }}" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">وصف الدورة</label>
                    <textarea name="description" id="description" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                              required>{{ old('description', $course->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">صورة الدورة</label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           {{ isset($course) ? '' : 'required' }}>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if(isset($course) && $course->image_url)
                        <div class="mt-2">
                            <img src="{{ $course->image_url }}" alt="Current course image" class="h-32 w-auto">
                        </div>
                    @endif
                </div>

                <!-- Course Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">مدة الدورة (بالساعات)</label>
                    <input type="number" name="duration" id="duration" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ old('duration', $course->duration ?? '') }}" required>
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">حالة الدورة</label>
                    <select name="status" id="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            required>
                        <option value="upcoming" {{ (old('status', $course->status ?? '') == 'upcoming') ? 'selected' : '' }}>قادم</option>
                        <option value="active" {{ (old('status', $course->status ?? '') == 'active') ? 'selected' : '' }}>نشط</option>
                        <option value="completed" {{ (old('status', $course->status ?? '') == 'completed') ? 'selected' : '' }}>مكتمل</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">القسم</label>
                    <select name="department_id" id="department_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            required>
                        <option value="">اختر القسم</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->id }}" {{ (old('department_id', $course->department_id ?? '') == $department->id) ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        {{ isset($course) ? 'تحديث الدورة' : 'إضافة الدورة' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 