<?php

function render_password_input($id, $name, $label) {
    return "
    <div class='auth-input-group'>
        <input type='password' name='{$name}' id='{$id}' 
               class='auth-input-modern' 
               placeholder=' '>
        <label class='auth-label-modern'>{$label}</label>
        <button type='button' class='password-toggle-btn' onclick=\"togglePasswordVisibility('{$id}')\">
            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'/>
                <path class='eye-slash hidden' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3l18 18' />
            </svg>
        </button>
    </div>";
}
?>
