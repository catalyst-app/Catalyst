() => {
  let optionalAttributes = {};

  if (this.properties.maxlength) {
    optionalAttributes["maxlength"] = this.properties.maxlength;
  }

  let className = "form-field";
  if (this.properties.required || this.properties.primary) {
    className += " active";
  }

  return (
    <div className="input-field col s12">
      <input id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
       name={ this.properties.distinguisher }
       type="text"
       form={ this.properties.formDistinguisher+"-form-element" }
       autocomplete={ this.properties.autocomplete }
       pattern={ this.properties.pattern }
       value={ this.properties.value }
       required={ this.properties.required }
       autofocus={ this.properties.primary }
       className={ className }
       { ...optionalAttributes } />
      <form-label data-properties={ JSON.stringify(this.properties) } />
      <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
    </div>
  );
};
