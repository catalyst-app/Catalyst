() => (
  <div className="input-field col s12">
    <select id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
     name={ this.properties.distinguisher }
     autocomplete={ this.properties.autocomplete }
     required={ this.properties.required }
     className="form-field">
      <option value="" selected={ this.properties.valueIsPrefilled || this.properties.value == "" }>Choose an option</option>
      { Object.keys(this.properties.options).map(key => (
        <option value={ key } selected={ this.properties.valueIsPrefilled && this.properties.value == key }>{ this.properties.options[key] }</option>
      )) }
    </select>
    <form-label data-properties={ JSON.stringify(this.properties) } />
    <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
  </div>
);
