package com.DBControll;

import java.sql.*;

public class DBController {
	
	public DBController(){

	}
	public DBController(String _driver, String _url, String _dbname){
		this.setDBDriver(_driver);
		this.setDBUrl(_url);
		this.setDBName(_dbname);
	}
	protected Connection con;
	private String m_tablename;
	private String m_day;
	private String m_driver;
	private String m_url;
	private String m_DBname;
	private String m_username;
	private String m_password;
	protected Statement m_stmt;
	
	
	public void setTablename(String _day){
		 
		m_tablename = _day;
	}
	
	public String getTablename(){
		
		return m_tablename;
		
	}
	
	public void setDay(String _day){
		 
		m_day = _day;
	}
	
	public String getDay(){
		
		return m_day;
		
	}
	
	public void setDBDriver(String _driver){
		 
		m_driver = _driver;
	}
	
	public String getDBDriver(){
		
		return m_driver;
		
	}
	
	
	public void setDBUrl(String _url){
		
		m_url = _url;
	}
	
	public String getDBUrl(){
		
		return m_url;
	}
	
	public void setDBName(String _DBname)
	{
		
		m_DBname = _DBname;
	}

	
	public String getDBName(){
		
		return m_DBname;
	}
	
	public void setUserName(String _username){
		
		m_username = _username;
		
	}
	
	public String getUserName(){
		
		return m_username;
		
	}
	
	
	public void setPassWord(String _password){
		
		m_password = _password;
	}
	
	public String getPassWord(){
		
		return m_password;
	}
	
	
	public ResultSet executeQuery(String _sql) throws SQLException{
		
       ResultSet result =  m_stmt.executeQuery(_sql);
		
		return result;
	}
	
	public int executeUpdate(String _sql) throws SQLException{
		
	       int result =  m_stmt.executeUpdate(_sql);
			
			return result;
	}
	
	public void connect() throws SQLException, ClassNotFoundException{

		 String Url = this.getDBUrl() +  this.getDBName();
		 Class.forName(this.getDBDriver());
		 System.out.println("Url:" + Url);
		 System.out.println("getDBDriver:" + this.getDBDriver());
		 con = DriverManager.getConnection(Url,this.getUserName(),this.getPassWord()); // 연결
       System.out.println("Mysql DB Connection.");
       m_stmt = con.createStatement();
       if(m_stmt == null)
       	 System.out.println("m_stmt null!!");
       
       if(con  == null)
       	System.out.println("con null!!");
	}
	
	
	public ResultSet selectTable(String _table){
		
		ResultSet result = null;
		String sqlCT = "select * from "  + _table;

		try {
			result= this.executeQuery(sqlCT);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return result;
	}
	

	
	public void sqlexecuteUpdate(String _sql){
		//create, delete ,update
		try {
			int result = this.executeUpdate(_sql);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	

	
	
	public ResultSet sqlexecuteQuery(String _sql){
		//select 
		ResultSet result = null;
		try {
			result= this.executeQuery(_sql);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return result;
		
	}
	
	public void InsertQuery(String[] _value, String _sql) throws SQLException, ClassNotFoundException{
		
		String strQuery =  _sql;//"INSERT INTO " +  this.getTablename() + " (keyword, sum, morpheme, keywordindex) VALUES(?,?,?,?)";

		PreparedStatement pstmt= con.prepareStatement(strQuery);

		pstmt.clearParameters();
		
		for(int i = 0; i < _value.length; i++)
		{
			pstmt.setString(i + 1,_value[i]);
			System.out.print(_value[i]);
			if(i == 1)
				pstmt.setInt(i + 1,  Integer.parseInt(_value[i]));
			
		}

		int rowCount = pstmt.executeUpdate();
		System.out.print(rowCount);

	}
	

}
