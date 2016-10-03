package com.WordFilter;

import java.io.IOException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.HashMap;


import com.DBControll.imfDBController;

public class FindOriginCountry {
	
	private HashMap<String, String> m_WordHashMap;
	imfDBController controller;

	
	public FindOriginCountry(){

		m_WordHashMap = new HashMap<String, String>();
	}
	
	public void loadData() 
			throws IOException, InterruptedException, SQLException
	{
		
		controller = new imfDBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/","imf_database");
		controller.setUserName("hive");
		controller.setPassWord("740412");
		try {
			controller.connect();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		this.CreateHashMap();
	}
	
	private void CreateHashMap() throws SQLException{
		
		ResultSet rs =  controller.GetCountryWordTable();
		
		while (rs.next()) {
			int i=1;
			String english  = rs.getString(i++);
			String chinese  = rs.getString(i++);
			String headchinese  = rs.getString(i++);
			String korea  = rs.getString(i++);
			String headkorea  = rs.getString(i++);
			m_WordHashMap.put(english, english);
			m_WordHashMap.put(chinese, english);
			m_WordHashMap.put(headchinese, english);
			m_WordHashMap.put(korea, english);
			m_WordHashMap.put(headkorea, english);
			
	    }
		
		
	}
   
	public String FindWord(String _str){
		String word = null;
		if(m_WordHashMap.get(_str)!= null)
			word = m_WordHashMap.get(_str);
		
		return word;
 		   
	}
	
	public boolean isKeyword(String _value){
		try {
			return controller.isKeywordData(_value);
		} catch (ClassNotFoundException | SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return false;
		
	}
	
	public void UpdateKeywordData(String[] _value){
		try {
			try {
				controller.UpdateKeywordData(_value);
			} catch (ClassNotFoundException | SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}
	
	public void InsertKeywordData(String[] _value){
		
		try {
			try {
				controller.InsertKeywordData(_value);
			} catch (ClassNotFoundException | SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}