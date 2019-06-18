() => {
  let className = "form-field";
  if (this.properties.value != null || this.properties.primary) {
    className += " active";
  }

  return (
    <div className="input-field col s12">
      <input id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
       name={ this.properties.distinguisher }
       type="email"
       autocomplete={ this.properties.autocomplete }
       pattern={ this.properties.pattern }
       maxlength={ this.properties.maxlength }
       value={ this.properties.value == null ? "" : this.properties.value }
       required={ this.properties.required }
       autofocus={ this.properties.primary }
       className={ className } />
      <form-label data-properties={ JSON.stringify(this.properties) } />
      <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
    </div>
  );
};
