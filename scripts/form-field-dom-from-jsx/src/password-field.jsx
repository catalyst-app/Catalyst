() => (
  <div className="input-field col s12">
    <input id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
     name={ this.properties.distinguisher }
     type="password"
     autocomplete={ this.properties.autocomplete }
     required={ this.properties.required }
     autofocus={ this.properties.primary }
     minlength={ this.properties.minlength }
     className="form-field" />
    <form-label data-properties={ JSON.stringify(this.properties) } />
    <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
  </div>
);
