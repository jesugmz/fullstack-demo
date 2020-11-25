import React from 'react';
import ReactDOM from 'react-dom';
import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import translationsEN from './locales/en.json';
import translationsES from './locales/es.json';
import Search from './components/Search';

i18n
.use(initReactI18next)
.init({
  resources: {
    en: {
      translation: translationsEN
    },
    es: {
      translation: translationsES
    }
  },
  lng: 'en',
  fallbackLng: 'en'
});

ReactDOM.render(
  <React.StrictMode>
    <Search />
  </React.StrictMode>,
  document.getElementById('root')
);
