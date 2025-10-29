<?php

namespace I18n;

class LanguageManager
{
    // Langue par défaut
    private const DEFAULT_LANGUAGE = 'fr';
    
    // Langues disponibles
    private const AVAILABLE_LANGUAGES = ['fr', 'en'];
    
    // Durée du cookie (30 jours)
    private const COOKIE_DURATION = 30 * 24 * 60 * 60;
    
    // Nom du cookie
    private const COOKIE_NAME = 'crewup_language';
    
    private array $translations;
    private string $currentLanguage;
    
    /**
     * Constructeur - Charge les traductions et définit la langue
     */
    public function __construct()
    {
        // Charge le fichier de traductions
        require_once __DIR__ . '/translations.php';
        $this->translations = $translations;
        
        // Détermine la langue à utiliser
        $this->currentLanguage = $this->determineLanguage();
    }
    
    /**
     * Détermine la langue à utiliser (cookie ou défaut)
     */
    private function determineLanguage(): string
    {
        // Vérifie si un cookie de langue existe et est valide
        if (isset($_COOKIE[self::COOKIE_NAME]) && 
            in_array($_COOKIE[self::COOKIE_NAME], self::AVAILABLE_LANGUAGES)) {
            return $_COOKIE[self::COOKIE_NAME];
        }
        
        // Sinon, retourne la langue par défaut
        return self::DEFAULT_LANGUAGE;
    }
    
    /**
     * Définit une nouvelle langue et crée le cookie
     */
    public function setLanguage(string $language): bool
    {
        // Vérifie que la langue est valide
        if (!in_array($language, self::AVAILABLE_LANGUAGES)) {
            return false;
        }
        
        // Crée le cookie
        setcookie(
            self::COOKIE_NAME,
            $language,
            time() + self::COOKIE_DURATION,
            '/', // Chemin (toute l'application)
            '', // Domaine
            false, // Secure (à mettre true en HTTPS)
            true // HttpOnly (sécurité)
        );
        
        // Met à jour la langue courante
        $this->currentLanguage = $language;
        
        return true;
    }
    
    /**
     * Récupère la langue courante
     */
    public function getCurrentLanguage(): string
    {
        return $this->currentLanguage;
    }
    
    /**
     * Récupère les langues disponibles
     */
    public function getAvailableLanguages(): array
    {
        return self::AVAILABLE_LANGUAGES;
    }
    
    /**
     * Traduit une clé (fonction principale)
     */
    public function translate(string $key): string
    {
        // verifier si au cas ou la traduction existe
        if (isset($this->translations[$this->currentLanguage][$key])) {
            return $this->translations[$this->currentLanguage][$key];
        }
        
        // Si la traduction n'existe pas, retourne la cle
        return $key;
    }
    

    public function t(string $key): string
    {
        return $this->translate($key);
    }
}