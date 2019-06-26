() => {
  let optionalAttributes = {};

  if (this.properties.maxlength) {
    optionalAttributes["maxlength"] = this.properties.maxlength;
  }

  return (
    <div className="input-field col s12">
      <input id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
       name={ this.properties.distinguisher }
       type="text"
       autocomplete={ this.properties.autocomplete }
       pattern={ this.properties.pattern }
       value={ this.properties.isFieldPrefilled ? this.properties.value : "" }
       required={ this.properties.required }
       autofocus={ this.properties.primary }
       className={ "form-field" + (this.properties.value != null || this.properties.primary ? " active" : "") }
       { ...optionalAttributes } />
      <form-label data-properties={ JSON.stringify(this.properties) } />
      <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
    </div>
  );
};
