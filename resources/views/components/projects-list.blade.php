<div class="p-4 sm:p-6 bg-slate-50 min-h-screen relative">
    
    @if (session()->has('message'))
        <div x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex justify-between items-center text-sm font-bold">
            <span>✔ {{ session('message') }}</span>
            <button @click="show = false" class="text-white">&times;</button>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-gray-800">إدارة المشاريع والشواغر</h2>
            <p class="text-sm text-gray-500 mt-1">قم بتوزيع المشاريع وتعيين المهام لفريق العمل.</p>
        </div>
        <button wire:click="openModal" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl transition shadow-md flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            إضافة مشروع جديد
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200">
                        <th class="p-4 whitespace-nowrap">المسمى الوظيفي</th>
                        <th class="p-4 whitespace-nowrap">العميل (الشركة)</th>
                        <th class="p-4 whitespace-nowrap">المسؤول</th>
                        <th class="p-4 text-center whitespace-nowrap">العدد المطلوب</th>
                        <th class="p-4 text-center whitespace-nowrap">الحالة</th>
                        <th class="p-4 whitespace-nowrap text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $project)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="p-4">
                                <p class="font-bold text-gray-800">{{ $project->title }}</p>
                                <p class="text-xs text-gray-400 mt-1">الراتب: {{ $project->offered_salary ? number_format($project->offered_salary).' ريال' : 'غير محدد' }}</p>
                            </td>
                            <td class="p-4 font-semibold text-gray-700">{{ $project->client->company_name ?? 'غير محدد' }}</td>
                            
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                        {{ substr($project->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $project->user->name ?? 'غير محدد' }}</span>
                                </div>
                            </td>

                            <td class="p-4 text-center font-black text-gray-700 text-lg">{{ $project->required_count }}</td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                    {{ $project->status === 'open' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ $project->status === 'open' ? 'مفتوح للتوظيف' : 'مغلق' }}
                                </span>
                            </td>
                            
                            <td class="p-4 flex gap-2 justify-end whitespace-nowrap items-center h-full">
                                @if($project->status === 'open')
                                    <button onclick="navigator.clipboard.writeText('{{ route('job.apply', $project->id) }}'); alert('تم نسخ الرابط العام للوظيفة! يمكنك لصقه في إعلانات السوشيال ميديا الآن.');" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-500 hover:text-white border border-emerald-200 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1" title="نسخ رابط التقديم للجمهور">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        إعلان
                                    </button>
                                @endif

                                <a href="{{ route('project.pipeline', $project->id) }}" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white border border-indigo-200 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                    إدارة
                                </a>
                                
                                <button wire:click="openModal({{ $project->id }})" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg transition" title="تعديل">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                
                                @can('is-admin')
                                <button wire:click="deleteProject({{ $project->id }})" onclick="confirm('هل أنت متأكد من حذف هذا المشروع؟') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition" title="حذف">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="font-bold text-gray-600 text-lg">لا توجد مشاريع حالياً.</p>
                                <p class="text-sm mt-1">ابدأ بإضافة مشروع جديد لفريق العمل.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-0 max-h-[90vh] overflow-y-auto border-t-4 border-blue-600">
                <div class="flex justify-between items-center p-6 border-b bg-slate-50">
                    <h3 class="text-xl font-black text-gray-800">{{ $editingProjectId ? 'تحديث بيانات المشروع' : 'إضافة مشروع / شاغر جديد' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-red-600 text-3xl transition">&times;</button>
                </div>

                <form wire:submit.prevent="saveProject" class="p-6">
                    
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-bold mb-2">المسمى الوظيفي *</label>
                        <input type="text" wire:model="title" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 font-semibold" placeholder="مثال: مهندس مدني، ممرضة...">
                        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">العميل (الشركة) *</label>
                            <select wire:model="client_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 bg-white font-semibold">
                                <option value="">-- اختر العميل --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-700">الموظف المسؤول *</label>
                            <select wire:model="user_id" class="w-full border-2 border-indigo-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 bg-indigo-50 font-bold text-indigo-900">
                                <option value="">-- تعيين لموظف --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role == 'admin' ? 'مدير' : 'أخصائي' }})</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-5">
                        <div class="w-full sm:w-1/2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">العدد المطلوب *</label>
                            <input type="number" wire:model="required_count" min="1" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 font-bold text-lg text-center">
                            @error('required_count') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full sm:w-1/2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">الراتب المقترح</label>
                            <div class="relative">
                                <input type="number" wire:model="offered_salary" class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-2.5 focus:outline-none focus:border-blue-500 font-bold">
                                <span class="absolute left-4 top-3 text-gray-400 text-sm">ريال</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-2">تفاصيل ومتطلبات الوظيفة</label>
                        <textarea wire:model="description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 text-sm" placeholder="اكتب الشروط، سنوات الخبرة المطلوبة..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-4">
                        <button type="button" wire:click="closeModal" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-xl transition shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            {{ $editingProjectId ? 'حفظ التعديلات' : 'اعتماد المشروع' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>