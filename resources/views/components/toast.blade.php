<div x-data="{
        show: false,
        message: '',
        type: 'success'
     }"
    @toast.window="
         message = $event.detail.message;
         type = $event.detail.type || 'success';
         show = true;
         setTimeout(() => show = false, 3000);
     "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-4 right-4 z-50 max-w-sm"
    style="display: none;">

    <div class="rounded-lg shadow-lg p-4 flex items-center gap-3 text-white"
        :class="{
             'bg-green-500': type === 'success',
             'bg-red-500': type === 'error',
             'bg-blue-500': type === 'info'
         }">
        <i class="text-2xl"
            :class="{
               'bi bi-check-circle-fill': type === 'success',
               'bi bi-exclamation-circle-fill': type === 'error',
               'bi bi-info-circle-fill': type === 'info'
           }"></i>
        <span x-text="message" class="font-medium"></span>
    </div>
</div>