@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-700 focus:border-accent dark:focus:border-accent focus:ring-accent dark:focus:ring-accent rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500']) }}>
