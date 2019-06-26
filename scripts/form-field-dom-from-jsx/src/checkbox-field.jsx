() => (
  <p class={ this.properties.size + " small-margin" }>
    <label for={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }>
      <input
       type="checkbox"
       id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
       class="filled-in"
       autocomplete="off"
       class="filled-in"
       checked={ this.properties.valueIsPrefilled ? this.properties.value : false } 
       required={ this.properties.required } />
      <span class="raw-inline-markdown">
        { this.properties.label }
        <span className={ "red-text" + ( this.properties.required ? "" : " hide" ) }>&nbsp;*</span>
        <span className="hide red-text">(error text)</span>
      </span>
    </label>
  </p>
);
