<%@ page import="<<$PACKAGE_NAME>>.<<$classname>>" errorPage="error.jsp" %>

<html>
<head>
  <title>Add a new <<$classname>></title>
</head>

<body>

<h2>Add a new <<$classname>></h2>

<form action="<<$classname>>Controller" method="post">

  <input type="hidden" name="ACTION" value="ADD">
  <input type="hidden" name="CURRENT_PAGE" value="<<$classname>>Add.jsp">

  <table>

<<* --- create an input field for each database table field --- *>>
<<section name=id loop=$fields>>
    <tr>
      <td align=left valign=top>
        <b><<$fields[id]>>:</b>
      </td>
      <td align=left valign=top>
        <input type="text" name="<<$fields[id]>>">
      </td>
    </tr>
<</section>>

    <!-- TODO: Change this for your own "Cancel" navigation. -->
    <tr>
      <td align=left valign=top>
        &nbsp;
      </td>
      <td align=left valign=top><input type=submit name="ADD" value="  Add  "><input
             type="button"
             value="  Cancel  "
             onClick="window.location.href='GO_SOMEWHERE.jsp'">
      </td>
    </tr>
  </table>

</form>
</body>
</html>
