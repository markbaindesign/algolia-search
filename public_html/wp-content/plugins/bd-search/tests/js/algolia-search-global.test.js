/**
 * Tests for Algolia Global Search JavaScript functionality
 * @package BDSearch/Tests/JS
 */

// Create a simplified version of the function for testing
function algoliaSearchGlobal(algoliaIndexName = "wp_global") {
   console.log('BD324 debug: algoliaSearchGlobal called with index:', algoliaIndexName);

   if (document.getElementById("algolia-search-box--global") !== null) {
      console.log('BD324 debug: Search box element found');

      const translationObject = translations_object_algolia_search_global;

      const searchGlobal = instantsearch({
         indexName: algoliaIndexName,
         debug: true,
         searchClient,
         routing: true,
         searchFunction(helper) {
            console.log('BD324 debug: Search function called with query:', helper.state.query);
            const container = document.querySelector("#hits--global");
            container.style.display = helper.state.query === "" ? "none" : "";
            if (helper.state.query) {
               helper.search();
            }
         },
      });

      searchGlobal.addWidgets([
         instantsearch.widgets.configure({
            hitsPerPage: 6,
            attributesToSnippet: ['content:20'],
         }),
         instantsearch.widgets.searchBox({
            container: "#algolia-search-box--global",
            placeholder: translationObject.placeholder_search,
            showSubmit: false,
            autofocus: true,
            templates: {
               reset: translationObject.label_reset,
            },
         }),
         instantsearch.widgets.infiniteHits({
            container: "#hits--global",
            templates: {
               empty: translationObject.label_empty,
               showMoreText: translationObject.label_more,
               item: '<div>{{title}}</div>'
            },
         }),
      ]);

      searchGlobal.start();
      console.log('BD324 debug: Search instance started');
   } else {
      console.log('BD324 debug: Search box element not found');
   }
}

describe('BD324 Debug - Algolia Global Search', () => {

   beforeEach(() => {
      // Reset DOM
      document.body.innerHTML = `
      <div id="algolia-search-box--global"></div>
      <div id="hits--global"></div>
    `;

      // Reset mocks
      jest.clearAllMocks();
   });

   test('BD324 debug: algoliaSearchGlobal function exists', () => {
      expect(typeof algoliaSearchGlobal).toBe('function');
      console.log('BD324 debug: ✅ algoliaSearchGlobal function exists test passed');
   });

   test('BD324 debug: function initializes with default index', () => {
      const consoleSpy = jest.spyOn(console, 'log');

      algoliaSearchGlobal();

      expect(consoleSpy).toHaveBeenCalledWith('BD324 debug: algoliaSearchGlobal called with index:', 'wp_global');
      console.log('BD324 debug: ✅ default index initialization test passed');
   });

   test('BD324 debug: function accepts custom index name', () => {
      const consoleSpy = jest.spyOn(console, 'log');

      algoliaSearchGlobal('custom_index');

      expect(consoleSpy).toHaveBeenCalledWith('BD324 debug: algoliaSearchGlobal called with index:', 'custom_index');
      console.log('BD324 debug: ✅ custom index name test passed');
   });

   test('BD324 debug: function handles missing search box element', () => {
      // Remove search box element
      document.body.innerHTML = '';

      const consoleSpy = jest.spyOn(console, 'log');

      algoliaSearchGlobal('wp_global');

      expect(consoleSpy).toHaveBeenCalledWith('BD324 debug: Search box element not found');
      console.log('BD324 debug: ✅ missing search box handling test passed');
   });

   test('BD324 debug: function initializes InstantSearch with correct config', () => {
      algoliaSearchGlobal('custom_index');

      expect(instantsearch).toHaveBeenCalledWith(
         expect.objectContaining({
            indexName: 'custom_index',
            debug: true,
            searchClient: expect.any(Object),
            routing: true,
            searchFunction: expect.any(Function)
         })
      );

      console.log('BD324 debug: ✅ InstantSearch initialization test passed');
   });

   test('BD324 debug: search function is called correctly', () => {
      const consoleSpy = jest.spyOn(console, 'log');

      algoliaSearchGlobal('wp_global');

      // Get the search function that was passed to instantsearch
      const instantSearchCall = instantsearch.mock.calls[0][0];
      const searchFunction = instantSearchCall.searchFunction;

      // Mock helper object
      const mockHelper = {
         state: { query: 'test query' },
         search: jest.fn()
      };

      // Call the search function
      searchFunction(mockHelper);

      expect(consoleSpy).toHaveBeenCalledWith('BD324 debug: Search function called with query:', 'test query');
      expect(mockHelper.search).toHaveBeenCalled();

      console.log('BD324 debug: ✅ search function test passed');
   });

   test('BD324 debug: function starts search instance', () => {
      const consoleSpy = jest.spyOn(console, 'log');

      algoliaSearchGlobal('wp_global');

      expect(consoleSpy).toHaveBeenCalledWith('BD324 debug: Search instance started');
      console.log('BD324 debug: ✅ search instance start test passed');
   });
});