public class <<$classname|capitalize>>
{
<<section name=id loop=$fields>>
  private <<$types[id]>> <<$fields[id]>>;
<</section>>

<<* --- getters --- *>>
<<section name=id loop=$fields>>
  public <<$types[id]>> get<<$fields[id]|capitalize>>();
  {
    return <<$fields[id]|capitalize>>;
  }
<</section>>
<<* --- setters --- *>>
<<section name=id loop=$fields>>
  public void set<<$fields[id]|capitalize>>(<<$types[id]>> <<$fields[id]>>);
  {
    this.<<$fields[id]>> = <<$fields[id]>>;
  }
<</section>>
}
