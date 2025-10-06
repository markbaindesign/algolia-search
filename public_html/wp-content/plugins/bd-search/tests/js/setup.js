/**
 * Jest Setup File for BD Search Plugin
 * Sets up testing environment for Algolia search functionality
 */

// Mock console methods for clean test output
global.console = {
   ...console,
   // Keep error and warn for debugging
   error: jest.fn(),
   warn: jest.fn(),
   // Mock log for BD324 debug messages
   log: jest.fn(),
};

// Mock DOM elements that Algolia search expects
document.body.innerHTML = `
  <div id="algolia-search-box--global"></div>
  <div id="hits--global"></div>
`;

// Mock Algolia global variables that would be localized by WordPress
global.algolia_vars = {
   app: 'test_app_id',
   search_key: 'test_search_key',
   debug: true
};

global.translations_object_algolia_search_global = {
   placeholder_search: 'Search',
   label_reset: 'Clear',
   label_empty: 'Nothing found',
   label_more: 'More'
};

global.algolia_active_lang_object = {
   active_language: 'en'
};

// Mock InstantSearch
global.instantsearch = jest.fn(() => ({
   addWidgets: jest.fn(),
   start: jest.fn(),
   helper: {
      setQuery: jest.fn().mockReturnThis(),
      search: jest.fn(),
      state: {
         query: ''
      }
   },
   on: jest.fn()
}));

// Mock InstantSearch widgets
global.instantsearch.widgets = {
   configure: jest.fn(() => ({})),
   searchBox: jest.fn(() => ({})),
   infiniteHits: jest.fn(() => ({}))
};

// Mock Algolia search client
global.searchClient = {
   search: jest.fn(() => Promise.resolve({ results: [] }))
};

// BD324 Debug mock for consistent testing
global.BD324_DEBUG = true;

console.log('BD324 debug: Jest setup completed - Algolia mocks initialized');

/**
 * Helper function to capture BD324 debug messages
 */
global.captureBD324Debug = () => {
   const debugMessages = [];
   const originalLog = console.log;

   console.log = jest.fn((message) => {
      if (message && message.includes && message.includes('BD324 debug')) {
         debugMessages.push(message);
      }
      originalLog(message);
   });

   return () => {
      console.log = originalLog;
      return debugMessages;
   };
};

/**
 * Helper to simulate DOM ready event
 */
global.simulateDOMReady = () => {
   const event = new Event('DOMContentLoaded');
   document.dispatchEvent(event);
};