() => (
  <div class="input-field col s12">
    <input
     type="text"
     class={ "datepicker " + (this.properties.primary || this.properties.valueIsPrefilled ? " active" : "") }
     id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
     autocomplete={ this.properties.autocomplete }
     required={ this.properties.required }
     autofocus={ this.properties.primary }
     value={ this.properties.valueIsPrefilled ? this.properties.value : "" } />
    <form-label data-properties={ JSON.stringify(this.properties) } />
    <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
  </div>
);
