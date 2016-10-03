package com.DBControll;
import java.sql.*;
import com.DBControll.DBController;


public class imfDBController extends DBController{
	
	public imfDBController(){

	}
	public imfDBController(String _driver, String _url, String _dbname){
		this.setDBDriver(_driver);
		this.setDBUrl(_url);
		this.setDBName(_dbname);
	}
	
	
	public boolean isSearchKeywordTable() throws SQLException, ClassNotFoundException{
		ResultSet result = null;
		String sqlCT = "select count(*) from SearchKeyword";
		int count = 0;
		try {
			result= this.executeQuery(sqlCT);
			//System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		while (result.next()) {
			count  = result.getInt("count(*)");
		}

		if(count == 0)
			return false;
		else
			return true;
	}
	
	
	
	public void UpdateSearchData(String _value) throws SQLException, ClassNotFoundException{
		String strQuery = "UPDATE SearchKeyword SET searchkeyword='" + _value + "' WHERE id=1"; // ?로 대치
		Statement stmt = null;
		stmt = con.createStatement();
		stmt.executeUpdate(strQuery);

	}
	
	
	
	public void InsertSearchData(String  _value) throws SQLException, ClassNotFoundException{
		
		String strQuery = "INSERT INTO SearchKeyword (searchkeyword) VALUES(?)"; // ?로 대치

		PreparedStatement pstmt= con.prepareStatement(strQuery);

		pstmt.clearParameters();
		
		
		pstmt.setString(1,_value);


		int rowCount = pstmt.executeUpdate();
		System.out.print(rowCount);

	}
	
	
	public boolean isKeywordData(String value) throws SQLException, ClassNotFoundException{
		String strQuery = "select count(*) from KeywordMatchGDP Where keyword='" + value + "'";
		
		ResultSet result = null;

		int count = 0;

		try {
			result= this.executeQuery(strQuery);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
			
		try {
			while (result.next()) {
				count  = result.getInt("count(*)");
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		if(count  == 0)
			return false;
		else 
			return true;
		
		
	}
	
	
	public void UpdateKeywordData(String[] _value) throws SQLException, ClassNotFoundException{
		
		String strQuery = "UPDATE KeywordMatchGDP SET country ='" + _value[1] + "' WHERE keyword ='" + _value[0] + "'"; // ?로 대치
		Statement stmt = null;
		System.out.println("update: " + strQuery);
		stmt = con.createStatement();
		stmt.executeUpdate(strQuery);
		
	}
	
	public void InsertKeywordData(String[] _value) throws SQLException, ClassNotFoundException{
		
		String strQuery = "INSERT INTO KeywordMatchGDP (keyword, country) VALUES(?,?)"; // ?로 대치

		PreparedStatement pstmt= con.prepareStatement(strQuery);

		pstmt.clearParameters();
		
		for(int i = 0; i < _value.length; i++)
		{
			pstmt.setString(i + 1,_value[i]);
			System.out.print(_value[i]);			
		}

		int rowCount = pstmt.executeUpdate();
		System.out.print(rowCount);

	}

	
	public ResultSet GetCountryWordTable(){
		ResultSet result = null;
		String sqlCT = "select * from countrywordtable";
	
		try {
			result= this.executeQuery(sqlCT);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return result;
	}
	
	
	public String GetSearchKeyword(){
		ResultSet result = null;
		String sqlCT = "select searchkeyword from SearchKeyword where id=1";
		String SearchKeywordString = null;
	
		try {
			result= this.executeQuery(sqlCT);
			//System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		

		try {
			while (result.next()) {
				SearchKeywordString  = result.getString("searchkeyword");
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return SearchKeywordString;
	}
	
	
}





