

import java.io.UnsupportedEncodingException;
import java.net.URLDecoder;
import java.net.URLEncoder;
import java.sql.ResultSet;
import java.sql.SQLException;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;
import net.sf.json.JSONArray;

import com.DBControll.SortDBController;

public class CollectTopKeyword {
	
	public SortDBController m_dbcontroller;
	
	CollectTopKeyword(){
		m_dbcontroller = null;
	}
	
	CollectTopKeyword(String dbinfo){
		
		
	}
	
	void connectDB( String driver, String address, String db, String username, String password) throws ClassNotFoundException, SQLException{
		m_dbcontroller = new SortDBController(driver,address,db);
		m_dbcontroller.setUserName(username);
		m_dbcontroller.setPassWord(password);
		m_dbcontroller.connect();
		
	}
	
	
	String getSelectSortTable(String tablename, String day, String keyword) throws ClassNotFoundException, SQLException, UnsupportedEncodingException{
	
       String[] charset = { "ISO8859-1","UTF-8"};
       String jsonstring = null;
		m_dbcontroller.setTablename(tablename);
		m_dbcontroller.setDay(day);
		String whereSQL = String.format(" where json_search(jsondata,'one','%s')='$.keyword'", keyword);
		ResultSet rs = null; 
		rs = m_dbcontroller.selectTable(m_dbcontroller.getDay() + m_dbcontroller.getTablename() + whereSQL);
		while (rs.next()) {
			 jsonstring = this.encodeTodecode(rs.getString("jsondata"),charset);
	    }
			
		return jsonstring;
	}
	
	  private String encodeTodecode(String _convertString, String[] charset) throws UnsupportedEncodingException{
	   

		 String encoded = URLEncoder.encode(_convertString, charset[0]);
	     String decoded = URLDecoder.decode(encoded, charset[1]);
		 return decoded;
		 
	 }
	  
	  
	  

	private String  createMultipleCallIndexSqlofWhere(JSONArray array){
		 StringBuilder sql = new StringBuilder();
		 sql.append(" where");
		  for (int i = 0; i < array.size(); i++){
			  String cindexstring  = null;
			   if(i < array.size() - 1 )
			      cindexstring = String.format(" cindex = %d or", array.get(i));
			   else
				   cindexstring = String.format(" cindex = %d", array.get(i));	 
			   
			   sql.append(cindexstring);
		  }
		  
		  return sql.toString();
	
	}
	  
	  
	String getSelectCrawlingRawTable(String tablename, String day, JSONArray array, String columns) throws ClassNotFoundException, SQLException, UnsupportedEncodingException{
			
		     //  String[] charset = { "ISO8859-1","UTF-8"};
		     //  String jsonstring = null;
				m_dbcontroller.setTablename(tablename);
				m_dbcontroller.setDay(day);
				String whereSQL = createMultipleCallIndexSqlofWhere(array);
				ResultSet rs = null; 
				rs = m_dbcontroller.selectTable(m_dbcontroller.getDay() + m_dbcontroller.getTablename() + whereSQL);
				StringBuilder result = new StringBuilder();
				int count = 0;
				while (rs.next()) {
					 //jsonstring = this.encodeTodecode(rs.getString(columns),charset);
					
					String titlestring  = rs.getString(columns) + "\n";
					result.append(String.format("count : %d title: %s",count,titlestring));
					count++;
					//System.out.println(String.format("count : %d", count));
			    }
					
				return result.toString();
	}
		


 }

