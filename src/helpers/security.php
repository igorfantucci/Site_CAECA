<?php
/**
 * Funções de Segurança e Sanitização
 * CAECA - UNIFEI
 */

if (!function_exists('e')) {
    /**
     * Escapa dados para saída HTML segura (Proteção contra XSS)
     */
    function e(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Limpa espaços e formata entradas de texto
     */
    function sanitize_input(?string $value): string {
        if ($value === null) return '';
        return trim(strip_tags($value));
    }
}
