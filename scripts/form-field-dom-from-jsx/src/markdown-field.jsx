() => {
  // only show note on first
  var firstMarkdownField = true;

  if (document.getElementsByTagName("markdown-field").length > 1) {
    if (document.getElementsByTagName("markdown-field")[0] != this) {
      var firstMarkdownField = false;
    }
  }

  return (
    <div>
      <p class={ "col s12 no-bottom-margin" + (firstMarkdownField ? "" : " hide") }>
        Catalyst supports a modified version of Markdown in fields like this.
        Please read <a href={ document.body.parentNode.getAttribute("data-rootdir") + "Markdown" }>this page</a> for help.
      </p>
      <div class="col s12">
        <div class="row">
          <div class="input-field col s12 m6">
            <textarea
             autocomplete={ this.properties.autocomplete }
             class="markdown-field materialize-textarea form-field"
             required={ this.properties.required }
             autofocus={ this.properties.primary }
             id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
             name={ this.properties.distinguisher }
             >
              { this.properties.valueIsPrefilled ? this.properties.value : "" }
            </textarea>
            <form-label data-properties={ JSON.stringify(this.properties) } />
            <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
          </div>

          <div class="col s12 m6 markdown-target markdown-preview raw-markdown" id={ "-preview-"+this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }>
            { this.properties.valueIsPrefilled ? this.properties.value : "" }
          </div>
        </div>
      </div>
    </div>
  );
};
