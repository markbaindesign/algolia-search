// Global Search
function algoliaSearchAdvanced(algoliaIndexName = "wp_global") {

   if (document.getElementById("algolia-searchbox--global--shortcode") !== null) {

      // Translation Object
      var translationObject = algolia_translations_object;

      const search = instantsearch({
         indexName: algoliaIndexName,
         debug: true,
         searchClient,
         routing: true,
         searchFunction(helper) {
            const container = document.querySelector("#algolia-hits--global--shortcode");
            container.style.display = helper.state.query === "" ? "none" : "";
            // Ensure we only trigger a search when there's a query
            if (helper.state.query) {
               helper.search();
            }
         },
      });

      // Widgets
      search.addWidgets([
         instantsearch.widgets.configure({
            hitsPerPage: 6,
            attributesToSnippet: ['content:20'],
         }),

         // Search box
         instantsearch.widgets.searchBox({
            container: "#algolia-searchbox--global--shortcode",
            placeholder: translationObject.placeholder_search,
            showSubmit: false,
            autofocus: true,
            templates: {
               reset: translationObject.label_reset,
            },
         }),

         // Results
         instantsearch.widgets.infiniteHits({
            container: "#algolia-hits--global--shortcode",
            templates: {
               empty: translationObject.label_empty,
               showMoreText: translationObject.label_more,
               item: `
                  <article itemtype="http://schema.org/Article" class="search__hits">
                  <div class="ais-hits--thumbnail search__result__image">
                     {{#image}}
                        <img src="{{ image }}" loading="lazy" width="100" height="100" alt="{{title}}" title="{{title}}" class="search-image" itemprop="image">
                     {{/image}}
                     </div>
                     <div class="search__result__main">
                        <a href="{{ url }}" title="{{ title }}" class="is-highlighted search__result__title">
                           {{#helpers.highlight}}
                              { "attribute": "title", "highlightedTagName": "mark"}
                           {{/helpers.highlight}}
                        </a>
                        <div class="search__result__meta">
                           {{#date}}
                              <div class="search__result__date">
                                 {{date}}
                              </div>
                           {{/date}}
                        </div>
                        {{#content}}
                           <p class="is-highlighted">
                              {{#helpers.snippet}}
                                 { "attribute": "content", "highlightedTagName": "mark" }
                              {{/helpers.snippet}}
                           </p>
                        {{/content}}
                     </div>
                  </article>
               `,
            },
         }),
      ]);

      search.start();

      // Clear search query & results
      function clearGlobalSearch() {
         search.helper.setQuery("").search;
      }

   }
}
