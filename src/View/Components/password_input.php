<?php

function render_password_input($id, $name, $label) {
    $btnId = "btn-toggle-" . $id;
    return "
    <div class='auth-input-group flex items-center w-full'>
        <div class='relative flex-grow'>
            <input type='password' name='{$name}' id='{$id}' 
                   class='auth-input-modern w-full' 
                   placeholder=' '
                   onfocus=\"document.getElementById('{$btnId}').classList.add('text-[#E50914]')\"
                   onblur=\"document.getElementById('{$btnId}').classList.remove('text-[#E50914]')\">
            <label class='auth-label-modern'>{$label}</label>
        </div>
        <button type='button' id='{$btnId}' class='password-toggle-btn text-zinc-500 transition-colors' onclick=\"togglePasswordVisibility('{$id}')\">
            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'/>
                <path class='eye-slash hidden' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3l18 18' />
            </svg>
        </button>
    </div>";
}
?>
