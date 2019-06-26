() => (
  <div className="input-field col s12">
    <input id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
     name={ this.properties.distinguisher }
     type="number"
     step={ 10**(-this.properties.precision) }
     inputmode={ this.properties.precision > 0 ? "decimal" : "numeric" }
     autocomplete={ this.properties.autocomplete }
     value={ this.properties.valueIsPrefilled ? this.properties.value : "" }
     required={ this.properties.required }
     autofocus={ this.properties.primary }
     className={ "form-field" + (this.properties.value != null || this.properties.primary ? " active" : "") }
     min={ this.properties.min }
     max={ this.properties.max } />
    <form-label data-properties={ JSON.stringify(this.properties) } />
    <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
  </div>
);
