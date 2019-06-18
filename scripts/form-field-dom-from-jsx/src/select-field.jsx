() => {
  return (
    <div className="input-field col s12">
      <select id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
       name={ this.properties.distinguisher }
       autocomplete={ this.properties.autocomplete }
       required={ this.properties.required }
       className="form-field">
        <option value="" selected={ this.properties.value == null || this.properties.value == "" }></option>
        { Object.keys(this.properties).forEach(function(key) {
          return <option value={ key } selected={ this.properties.value == key }>{ this.properties.options[key] }</option>
        }) }
      </select>
      <form-label data-properties={ JSON.stringify(this.properties) } />
      <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
    </div>
  );
};
