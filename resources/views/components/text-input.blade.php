@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 dark:border-slate-600 focus:border-emerald-500 focus:ring-emerald-500 dark:focus:border-emerald-500 dark:focus:ring-emerald-500 rounded-lg shadow-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500']) }}>
